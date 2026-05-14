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
            $isReferal        = filter_var($data['is_referal'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $referalCustomerId = $data['referal_customer_id'] ?? null;
            $nominalReferal   = (float) ($data['nominal_referal'] ?? 0);
            $discountReferal  = (float) ($data['use_discount_referal'] ?? 0);

            if ($isReferal && $referalCustomerId && $nominalReferal > 0) {
                $this->addReferalBalance($referalCustomerId, $nominalReferal);
            }

            if ($discountReferal > 0) {
                $this->deductReferalBalance($transaction->customer_id, $discountReferal);
            }
        });
    }

    public function processReferalOnEdit(Transaction $transaction, array $data, array $oldData = []): void
    {
        DB::transaction(function () use ($transaction, $data, $oldData) {
            $newIsReferal    = filter_var($data['is_referal'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $newCustomerId   = $data['referal_customer_id'] ?? null;
            $newNominal      = (float) ($data['nominal_referal'] ?? 0);

            $oldIsReferal    = (bool) ($oldData['is_referal'] ?? false);
            $oldCustomerId   = $oldData['referal_customer_id'] ?? null;
            $oldNominal      = (float) ($oldData['nominal_referal'] ?? 0);

            if (! $newIsReferal && ! $oldIsReferal) {
                return;
            }

            if ($oldIsReferal && ! $newIsReferal && $oldCustomerId) {
                $this->deductReferalBalance($oldCustomerId, $oldNominal);
                return;
            }

            if ($newIsReferal && $newCustomerId && $newNominal > 0) {

                $existingReferal = Referal::where('customer_id', $newCustomerId)->first();

                if (! $oldIsReferal) {
                    $this->addReferalBalance($newCustomerId, $newNominal);
                    return;
                }

                if ($oldCustomerId !== $newCustomerId) {
                    if ($oldCustomerId) {
                        $this->deductReferalBalance($oldCustomerId, $oldNominal);
                    }
                    $this->addReferalBalance($newCustomerId, $newNominal);
                    return;
                }

                if (! $existingReferal) {
                    $this->addReferalBalance($newCustomerId, $newNominal);
                    return;
                }

                if ($newNominal !== $oldNominal) {
                    $difference = $newNominal - $oldNominal;
                    if ($difference > 0) {
                        $this->addReferalBalance($newCustomerId, $difference);
                    } elseif ($difference < 0) {
                        $this->deductReferalBalance($newCustomerId, abs($difference));
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
