<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    public function __construct(
        protected ?string $dateFrom = null,
        protected ?string $dateUntil = null,
    ) {}

    public function collection(): Collection
    {
        $transactions = Transaction::query()
            ->with([
                'customer',
                'transactionItems.product',
                'transactionItems.bundle.bundleItems.product',
                'transactionDownPayments',
                'transactionPayment',
            ])
            ->when($this->dateFrom, fn($q) => $q->whereDate('transaction_date', '>=', $this->dateFrom))
            ->when($this->dateUntil, fn($q) => $q->whereDate('transaction_date', '<=', $this->dateUntil))
            ->get();

        $flattened = collect();

        foreach ($transactions as $transaction) {
            $payment = $transaction->transactionPayment;

            $paymentAmount  = (int) ($payment?->amount ?? 0);
            $paymentMethod  = $payment?->method?->getLabel();
            $paymentPaidAt  = $payment?->paid_at;

            if ($transaction->is_down_payment) {
                $dpInstallments = (int) $transaction->transactionDownPayments->sum('amount');

                $lastDp = $transaction->transactionDownPayments
                    ->sortByDesc('paid_at')
                    ->first();

                $dpMethod = $lastDp?->method_payment?->getLabel();

                // DP = TransactionPayment.amount
                $dpAmount = $paymentAmount > 0
                    ? $paymentAmount
                    : null;

                // Pelunasan = Grand Total - DP
                $pelunasan = (int) $transaction->grand_total - $paymentAmount;

                // Sudah lunas jika DP + cicilan DP = Grand Total
                $isLunas = (int) $transaction->grand_total ===
                    ($paymentAmount + $dpInstallments);

                $tanggalPelunasan = $isLunas
                    ? $paymentPaidAt
                    : null;
            } else {
                $dpAmount = null;
                $dpMethod = null;
                $pelunasan = $paymentAmount;
                $tanggalPelunasan = $paymentPaidAt;
            }

            $pembayaranPelunasan = $paymentMethod;

            $isFirstRow = true;

            $pushRow = function ($jumlah, $barang, $harga) use (
                $transaction,
                &$isFirstRow,
                $dpAmount,
                $dpMethod,
                $pelunasan,
                $pembayaranPelunasan,
                $tanggalPelunasan,
                $flattened
            ) {
                $flattened->push((object) [
                    'tanggal'           => $isFirstRow ? $transaction->transaction_date : null,
                    'nama'              => $isFirstRow ? $transaction->customer?->name : null,
                    'alamat'            => $isFirstRow ? $transaction->customer?->address : null,
                    'telpon'            => $isFirstRow ? $transaction->customer?->phone : null,
                    'jumlah_barang'     => $jumlah,
                    'barang'            => $barang,
                    'harga'             => $harga,
                    'ongkir'            => $isFirstRow ? $transaction->shiping_cost : null,
                    'dp'                => $isFirstRow ? $dpAmount : null,
                    'pembayaran_dp'     => $isFirstRow ? $dpMethod : null,
                    'pelunasan'         => $isFirstRow ? $pelunasan : null,
                    'pembayaran_lunas'  => $isFirstRow ? $pembayaranPelunasan : null,
                    'tanggal_pelunasan' => $isFirstRow ? $tanggalPelunasan : null,
                ]);
                $isFirstRow = false;
            };

            if ($transaction->transactionItems->isEmpty()) {
                $pushRow(null, null, null);
                continue;
            }

            foreach ($transaction->transactionItems as $item) {
                if ($item->bundle_id && $item->bundle) {
                    foreach ($item->bundle->bundleItems as $bundleItem) {
                        $pushRow(
                            $bundleItem->qty * $item->qty,
                            $bundleItem->product?->name,
                            (int) $item->subtotal,
                        );
                    }
                } else {
                    $pushRow(
                        $item->qty,
                        $item->product?->name,
                        (int) $item->subtotal,
                    );
                }
            }
        }

        return $flattened;
    }

    public function headings(): array
    {
        return [
            'TANGGAL',
            'NAMA',
            'ALAMAT',
            'NO TELPON',
            'JUMLAH BARANG',
            'BARANG',
            'HARGA',
            'ONGKIR',
            'DP',
            'PEMBAYARAN',
            'PELUNASAN',
            'PEMBAYARAN PELUNASAN',
            'TANGGAL PELUNASAN',
        ];
    }

    public function map($row): array
    {
        return [
            $row->tanggal ? Carbon::parse($row->tanggal)->format('d/m/Y') : null,
            $row->nama,
            $row->alamat,
            $row->telpon,
            $row->jumlah_barang,
            $row->barang,
            $row->harga !== null ? (int) $row->harga : null,
            $row->ongkir !== null ? (int) $row->ongkir : null,
            $row->dp !== null ? (int) $row->dp : null,
            $row->pembayaran_dp,
            $row->pelunasan !== null ? (int) $row->pelunasan : null,
            $row->pembayaran_lunas,
            $row->tanggal_pelunasan ? Carbon::parse($row->tanggal_pelunasan)->format('d/m/Y') : null,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet      = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $centerColumns = ['D', 'E', 'G', 'H', 'I', 'J', 'K', 'L'];

                foreach ($centerColumns as $column) {
                    $sheet->getStyle("{$column}1:{$column}{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                $sheet->getStyle("A1:M1")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A1:M1")
                    ->getFont()
                    ->setBold(true);
                foreach (['G', 'H', 'I', 'K'] as $column) {
                    $sheet->getStyle("{$column}2:{$column}{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                }
            },
        ];
    }
}
