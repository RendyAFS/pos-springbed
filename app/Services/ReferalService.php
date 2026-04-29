<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Referal;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ReferalService
{
    /**
     * Generate referal code unik dengan format: timestamp + 5 karakter alphanumeric
     */
    public function generateReferalCode(): string
    {
        $timestamp = time();
        $random    = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5));

        return $timestamp . $random;
    }

    /**
     * Proses referal saat CREATE transaksi.
     * - Tambah saldo ke customer yang mereferralkan
     * - Kurangi saldo customer transaksi jika pakai discount_referal
     */
    public function processReferal(Transaction $transaction, array $data): void
    {
        DB::transaction(function () use ($transaction, $data) {
            $isReferal         = filter_var($data['is_referal'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $referalCustomerId = $data['referal_customer_id'] ?? null;
            $nominalReferal    = (float) ($data['nominal_referal'] ?? 0);
            $discountReferal   = (float) ($data['discount_referal'] ?? 0);

            // ── 1. Tambah saldo ke customer yang mereferralkan ────────────────
            if ($isReferal && $referalCustomerId && $nominalReferal > 0) {
                $this->addReferalBalance($referalCustomerId, $nominalReferal);
            }

            // ── 2. Kurangi saldo customer transaksi jika pakai discount referal ─
            if ($discountReferal > 0) {
                $this->deductReferalBalance($transaction->customer_id, $discountReferal);
            }
        });
    }

    /**
     * Proses referal saat EDIT transaksi.
     *
     * Logika:
     * - Bandingkan referal_customer_id & nominal_referal lama vs baru
     * - Jika referal_customer_id sama → hitung selisih nominal dan increment/decrement
     * - Jika referal_customer_id berubah → rollback saldo lama, tambah ke customer baru
     * - Jika is_referal dimatikan → rollback saldo yang pernah diberikan
     * - discount_referal tidak diproses ulang di edit (sudah dikurangi saat create)
     */
    public function processReferalOnEdit(Transaction $transaction, array $data): void
    {
        DB::transaction(function () use ($transaction, $data) {
            $isReferalBaru         = filter_var($data['is_referal'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $referalCustomerIdBaru = $data['referal_customer_id'] ?? null;
            $nominalBaru           = (float) ($data['nominal_referal'] ?? 0);

            // Ambil nilai lama dari record (sebelum disave — record sudah di-save Filament,
            // tapi kolom referal belum berubah karena kita simpan di extraData sebelum mutate)
            $transaction->refresh();
            $isReferalLama         = (bool) $transaction->is_referal;
            $referalCustomerIdLama = $transaction->referal_customer_id;
            $nominalLama           = (float) ($transaction->nominal_referal ?? 0);

            // ── Tidak ada perubahan sama sekali, skip ─────────────────────────
            $tidakAda = ! $isReferalBaru && ! $isReferalLama;
            if ($tidakAda) {
                return;
            }

            // ── Case 1: Referal dimatikan (was true, now false) ───────────────
            if ($isReferalLama && ! $isReferalBaru && $referalCustomerIdLama) {
                // Rollback saldo yang pernah diberikan
                $this->deductReferalBalance($referalCustomerIdLama, $nominalLama);
                return;
            }

            // ── Case 2: Referal baru dinyalakan (was false, now true) ─────────
            if (! $isReferalLama && $isReferalBaru && $referalCustomerIdBaru && $nominalBaru > 0) {
                $this->addReferalBalance($referalCustomerIdBaru, $nominalBaru);
                return;
            }

            // ── Case 3: Referal sudah aktif, cek perubahan ───────────────────
            if ($isReferalLama && $isReferalBaru) {

                // Ganti customer referal
                if ($referalCustomerIdLama !== $referalCustomerIdBaru) {
                    // Rollback saldo ke customer lama
                    if ($referalCustomerIdLama) {
                        $this->deductReferalBalance($referalCustomerIdLama, $nominalLama);
                    }
                    // Tambah saldo ke customer baru
                    if ($referalCustomerIdBaru && $nominalBaru > 0) {
                        $this->addReferalBalance($referalCustomerIdBaru, $nominalBaru);
                    }
                    return;
                }

                // Customer sama, nominal berubah
                if ($referalCustomerIdLama && $nominalBaru !== $nominalLama) {
                    $selisih = $nominalBaru - $nominalLama;
                    if ($selisih > 0) {
                        $this->addReferalBalance($referalCustomerIdLama, $selisih);
                    } elseif ($selisih < 0) {
                        $this->deductReferalBalance($referalCustomerIdLama, abs($selisih));
                    }
                }
            }
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function addReferalBalance(int $customerId, float $amount): void
    {
        $customer = Customer::find($customerId);
        if (! $customer) return;

        $existing = Referal::where('customer_id', $customerId)->lockForUpdate()->first();

        if ($existing) {
            $existing->increment('discount_amount', $amount);
        } else {
            Referal::create([
                'customer_id'     => $customerId,
                'name_customer'   => $customer->name,
                'referal_code'    => $this->generateReferalCode(),
                'discount_amount' => $amount,
            ]);
        }
    }

    private function deductReferalBalance(int $customerId, float $amount): void
    {
        $referal = Referal::where('customer_id', $customerId)->lockForUpdate()->first();
        if (! $referal || $referal->discount_amount <= 0) return;

        $deduct = min($amount, (float) $referal->discount_amount);
        $referal->decrement('discount_amount', $deduct);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Public helpers
    // ─────────────────────────────────────────────────────────────────────────

    public function getReferalBalance(int $customerId): float
    {
        $referal = Referal::where('customer_id', $customerId)->first();
        return $referal ? (float) $referal->discount_amount : 0.0;
    }

    public function hasReferalBalance(int $customerId): bool
    {
        return $this->getReferalBalance($customerId) > 0;
    }
}
