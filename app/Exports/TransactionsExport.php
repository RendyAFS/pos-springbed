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
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    public function __construct(
        protected ?string $dateFrom = null,
        protected ?string $dateUntil = null,
        protected bool $canViewHargaNetto = false,
        protected array $createdBy = [],
    ) {}

    public function collection(): Collection
    {
        $transactions = Transaction::query()
            ->with([
                'customer',
                'creator',
                'transactionItems.product',
                'transactionItems.bundle.bundleItems.product',
                'transactionDownPayments',
                'transactionPayment',
                'transactionShipment',
            ])
            ->when($this->dateFrom, fn($q) => $q->whereDate('transaction_date', '>=', $this->dateFrom))
            ->when($this->dateUntil, fn($q) => $q->whereDate('transaction_date', '<=', $this->dateUntil))
            ->when(!empty($this->createdBy), fn($q) => $q->whereIn('created_by', $this->createdBy))
            ->get();

        $flattened = collect();

        foreach ($transactions as $transaction) {
            $payment = $transaction->transactionPayment;
            $paymentAmount  = (int) ($payment?->amount ?? 0);
            $paymentMethod  = $payment?->method?->getLabel();
            $paymentPaidAt  = $payment?->paid_at;

            if ($transaction->is_down_payment) {
                $dpInstallments = (int) $transaction->transactionDownPayments->sum('amount');
                $lastDp = $transaction->transactionDownPayments->sortByDesc('paid_at')->first();
                $dpMethod = $lastDp?->method_payment?->getLabel();

                $dpAmount = $paymentAmount > 0 ? $paymentAmount : null;
                $pelunasan = (int) $transaction->grand_total - $paymentAmount;

                $isLunas = (int) $transaction->grand_total === ($paymentAmount + $dpInstallments);
                $tanggalPelunasan = $isLunas ? $paymentPaidAt : null;
            } else {
                $dpAmount = null;
                $dpMethod = null;
                $pelunasan = $paymentAmount;
                $tanggalPelunasan = $paymentPaidAt;
            }
            $pembayaranPelunasan = $paymentMethod;

            $totalDp = (int) $transaction->transactionDownPayments->sum('amount');
            $totalPaid = $totalDp + $paymentAmount;
            $grandTotal = (int) $transaction->grand_total;
            $sisa = max(0, $grandTotal - $totalPaid);

            if ($totalPaid >= $grandTotal && $grandTotal > 0) {
                $statusPembayaran = 'Lunas';
            } elseif ($totalPaid > 0) {
                $statusPembayaran = 'DP';
            } else {
                $statusPembayaran = 'Sisa';
            }

            $tanggalKirim = $transaction->transactionShipment?->created_at ?? null;

            $isFirstRow = true;

            $pushRow = function ($jumlah, $barang, $harga, $hargaNetto) use (
                $transaction,
                &$isFirstRow,
                $dpAmount,
                $dpMethod,
                $statusPembayaran,
                $pelunasan,
                $pembayaranPelunasan,
                $tanggalPelunasan,
                $sisa,
                $tanggalKirim,
                $flattened
            ) {
                $flattened->push((object) [
                    'tanggal'              => $isFirstRow ? $transaction->transaction_date : null,
                    'nama'                 => $isFirstRow ? $transaction->customer?->name : null,
                    'alamat'               => $isFirstRow ? $transaction->customer?->address : null,
                    'telpon'               => $isFirstRow ? $transaction->customer?->phone : null,
                    'jumlah_barang'        => $jumlah,
                    'barang'               => $barang,
                    'harga'                => $harga,
                    'harga_netto'          => $hargaNetto,
                    'ongkir'               => $isFirstRow ? $transaction->shiping_cost : null,
                    'tanggal_kirim'        => $isFirstRow ? $tanggalKirim : null,
                    'dp'                   => $isFirstRow ? $dpAmount : null,
                    'pembayaran'           => $isFirstRow ? $dpMethod : null,
                    'status_pembayaran'    => $isFirstRow ? $statusPembayaran : null,
                    'pelunasan'            => $isFirstRow ? $pelunasan : null,
                    'pembayaran_pelunasan' => $isFirstRow ? $pembayaranPelunasan : null,
                    'tanggal_pelunasan'    => $isFirstRow ? $tanggalPelunasan : null,
                    'sisa_pembayaran'      => $isFirstRow ? $sisa : null,
                    'created_by_name'      => $isFirstRow ? $transaction->creator?->name : null,
                ]);
                $isFirstRow = false;
            };

            if ($transaction->transactionItems->isEmpty()) {
                $pushRow(null, null, null, null);
                continue;
            }

            foreach ($transaction->transactionItems as $item) {
                $hargaNetto = $this->canViewHargaNetto
                    ? (($item->selling_price ?? 0) - ($item->discount ?? 0))
                    : null;

                if ($item->bundle_id && $item->bundle) {

                    $bundleItems = $item->bundle->bundleItems;

                    $totalBundlePrice = $bundleItems->sum(function ($bundleItem) {
                        return ($bundleItem->price ?? 0) * ($bundleItem->qty ?? 1);
                    });

                    foreach ($bundleItems as $bundleItem) {

                        $componentPrice = ($bundleItem->price ?? 0) * ($bundleItem->qty ?? 1);

                        $ratio = $totalBundlePrice > 0
                            ? $componentPrice / $totalBundlePrice
                            : 0;

                        $allocatedHarga = round(($item->subtotal ?? 0) * $ratio);

                        $allocatedNetto = $this->canViewHargaNetto
                            ? round($hargaNetto * $ratio)
                            : null;

                        $pushRow(
                            $bundleItem->qty * $item->qty,
                            $bundleItem->product?->name,
                            $allocatedHarga,
                            $allocatedNetto
                        );
                    }
                } else {

                    $pushRow(
                        $item->qty,
                        $item->product?->name,
                        (int) $item->subtotal,
                        $hargaNetto
                    );
                }
            }
        }

        return $flattened;
    }

    public function headings(): array
    {
        $headings = [
            'TANGGAL',
            'NAMA',
            'ALAMAT',
            'NO TELPON',
            'JUMLAH BARANG',
            'BARANG',
            'HARGA',
        ];

        if ($this->canViewHargaNetto) {
            $headings[] = 'HARGA NETTO';
        }

        $headings = array_merge($headings, [
            'ONGKIR',
            'TANGGAL KIRIM',
            'DP',
            'PEMBAYARAN',
            'STATUS PEMBAYARAN',
            'PELUNASAN',
            'PEMBAYARAN PELUNASAN',
            'TANGGAL PELUNASAN',
            'SISA',
            'CREATED BY',
        ]);

        return $headings;
    }

    public function map($row): array
    {
        $map = [
            $row->tanggal ? Carbon::parse($row->tanggal)->format('d/m/Y') : null,
            $row->nama,
            $row->alamat,
            $row->telpon,
            $row->jumlah_barang,
            $row->barang,
            $row->harga !== null ? (int) $row->harga : null,
        ];

        if ($this->canViewHargaNetto) {
            $map[] = $row->harga_netto !== null ? (int) $row->harga_netto : null;
        }

        $map = array_merge($map, [
            $row->ongkir !== null ? (int) $row->ongkir : null,
            $row->tanggal_kirim ? Carbon::parse($row->tanggal_kirim)->format('d/m/Y') : null,
            $row->dp !== null ? (int) $row->dp : null,
            $row->pembayaran,
            $row->status_pembayaran,
            $row->pelunasan !== null ? (int) $row->pelunasan : null,
            $row->pembayaran_pelunasan,
            $row->tanggal_pelunasan ? Carbon::parse($row->tanggal_pelunasan)->format('d/m/Y') : null,
            $row->sisa_pembayaran !== null ? (int) $row->sisa_pembayaran : null,
            $row->created_by_name,
        ]);

        return $map;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet      = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $cols = [
                    'telpon' => 'D',
                    'jumlah_barang' => 'E',
                    'harga' => 'G',
                ];

                $currentIdx = 7;
                if ($this->canViewHargaNetto) {
                    $cols['harga_netto'] = chr(65 + $currentIdx);
                    $currentIdx++;
                }
                $cols['ongkir']               = chr(65 + $currentIdx++);
                $cols['tanggal_kirim']        = chr(65 + $currentIdx++);
                $cols['dp']                   = chr(65 + $currentIdx++);
                $cols['pembayaran']           = chr(65 + $currentIdx++);
                $cols['status_pembayaran']    = chr(65 + $currentIdx++);
                $cols['pelunasan']            = chr(65 + $currentIdx++);
                $cols['pembayaran_pelunasan'] = chr(65 + $currentIdx++);
                $cols['tanggal_pelunasan']    = chr(65 + $currentIdx++);
                $cols['sisa_pembayaran']      = chr(65 + $currentIdx++);
                $cols['created_by_name']      = chr(65 + $currentIdx++);

                $lastCol = $cols['created_by_name'];

                $centerColumns = [
                    $cols['telpon'],
                    $cols['jumlah_barang'],
                    $cols['harga'],
                    $cols['ongkir'],
                    $cols['tanggal_kirim'],
                    $cols['dp'],
                    $cols['pembayaran'],
                    $cols['status_pembayaran'],
                    $cols['pelunasan'],
                    $cols['pembayaran_pelunasan'],
                    $cols['tanggal_pelunasan'],
                    $cols['sisa_pembayaran'],
                    $cols['created_by_name'],
                ];

                foreach ($centerColumns as $column) {
                    $sheet->getStyle("{$column}1:{$column}{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                $sheet->getStyle("A1:{$lastCol}1")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A1:{$lastCol}1")
                    ->getFont()
                    ->setBold(true);

                $numberFormatCols = [
                    $cols['harga'],
                    $cols['ongkir'],
                    $cols['dp'],
                    $cols['pelunasan'],
                    $cols['sisa_pembayaran'],
                ];

                if ($this->canViewHargaNetto) {
                    $numberFormatCols[] = $cols['harga_netto'];
                }

                foreach ($numberFormatCols as $column) {
                    $sheet->getStyle("{$column}2:{$column}{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                }
            },
        ];
    }
}
