<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Referal;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ReferalService
{
    public function generateReferalCode(): string
    {
        $timestamp = time();
        $random    = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5));

        return $timestamp . $random;
    }

    public function processReferal(Transaction $transaction, array $data): void
    {
        DB::transaction(function () use ($transaction, $data) {
            $isReferal         = filter_var($data['is_referal'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $referalCustomerId = $data['referal_customer_id'] ?? null;
            $nominalReferal    = (float) ($data['nominal_referal'] ?? 0);
            $discountReferal   = (float) ($data['use_discount_referal'] ?? 0);

            if ($isReferal && $referalCustomerId && $nominalReferal > 0) {
                $this->addReferalBalance($referalCustomerId, $nominalReferal);
            }

            if ($discountReferal > 0) {
                $this->deductReferalBalance($transaction->customer_id, $discountReferal);
            }
        });
    }

    public function processReferalOnEdit(Transaction $transaction, array $data): void
    {
        DB::transaction(function () use ($transaction, $data) {
            $isReferalBaru         = filter_var($data['is_referal'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $referalCustomerIdBaru = $data['referal_customer_id'] ?? null;
            $nominalBaru           = (float) ($data['nominal_referal'] ?? 0);

            $transaction->refresh();
            $isReferalLama         = (bool) $transaction->is_referal;
            $referalCustomerIdLama = $transaction->referal_customer_id;
            $nominalLama           = (float) ($transaction->nominal_referal ?? 0);

            $tidakAda = ! $isReferalBaru && ! $isReferalLama;
            if ($tidakAda) {
                return;
            }

            if ($isReferalLama && ! $isReferalBaru && $referalCustomerIdLama) {
                $this->deductReferalBalance($referalCustomerIdLama, $nominalLama);
                return;
            }

            if (! $isReferalLama && $isReferalBaru && $referalCustomerIdBaru && $nominalBaru > 0) {
                $this->addReferalBalance($referalCustomerIdBaru, $nominalBaru);
                return;
            }

            if ($isReferalLama && $isReferalBaru) {

                if ($referalCustomerIdLama !== $referalCustomerIdBaru) {
                    if ($referalCustomerIdLama) {
                        $this->deductReferalBalance($referalCustomerIdLama, $nominalLama);
                    }
                    if ($referalCustomerIdBaru && $nominalBaru > 0) {
                        $this->addReferalBalance($referalCustomerIdBaru, $nominalBaru);
                    }
                    return;
                }

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
