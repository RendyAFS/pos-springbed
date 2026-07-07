<?php

namespace App\Filament\Resources\Transactions\Support;

use App\Enums\PaymentMethodDpEnum;
use App\Enums\TransactionPaymentStatusEnum;
use App\Enums\TransactionStatusEnum;
use App\Helpers\RupiahHelper;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Text;
use Filament\Support\RawJs;
use Illuminate\Support\HtmlString;
use Filament\Actions\ActionGroup;
use App\Filament\Resources\Transactions\TransactionResource;

class TransactionActions
{
    /**
     * Action yang dipakai di Table maupun Kanban.
     */
    public static function table(): ActionGroup
    {
        return ActionGroup::make([
            static::sendWhatsapp(),
            static::updateStatus(),
            static::print(),
            static::invoiceA5(),
            static::invoiceA4(),
            static::addDownPayment(),
            static::edit(),
            static::delete(),
            static::forceDelete(),
            static::restore(),
        ])
            ->icon(Heroicon::OutlinedEllipsisHorizontal);
    }

    public static function kanbanIcons(): array
    {
        return [
            Action::make('view')
                ->label('View')
                ->icon(Heroicon::Eye)
                ->color('gray')
                ->url(fn($record) => TransactionResource::getUrl('view', [
                    'record' => $record,
                ])),

            Action::make('edit')
                ->label('Edit')
                ->icon(Heroicon::PencilSquare)
                ->color('warning')
                ->url(fn($record) => TransactionResource::getUrl('edit', [
                    'record' => $record,
                ])),
        ];
    }

    public static function kanbanDropdown(): array
    {
        return [
            static::sendWhatsapp(),
            static::updateStatus(),
            static::print(),
            static::invoiceA5(),
            static::invoiceA4(),
            static::addDownPayment(),
        ];
    }

    public static function updateStatus(): Action
    {
        return Action::make('updateAllStatus')
            ->label('Update Status')
            ->icon(Heroicon::ArrowPath)
            ->color('warning')
            ->modalHeading('Update Status Transaction')
            ->modalDescription(fn($record) => "Transaction: {$record->transaction_code}")
            ->modalWidth('sm')
            ->schema([
                Select::make('status')
                    ->label('Status Transaction')
                    ->options(
                        collect(TransactionStatusEnum::cases())
                            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                            ->toArray()
                    )
                    ->default(fn($record) => $record->status?->value)
                    ->native(false),

                Select::make('payment_status')
                    ->label('Status Payment')
                    ->options(
                        collect(TransactionPaymentStatusEnum::cases())
                            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                            ->toArray()
                    )
                    ->default(fn($record) => $record->transactionPayment?->status?->value)
                    ->visible(fn($record) => $record->transactionPayment !== null)
                    ->native(false),

                TextInput::make('tracking_number')
                    ->label('No. Resi')
                    ->default(fn($record) => $record->transactionShipment?->tracking_number)
                    ->visible(fn($record) => $record->transactionShipment !== null),
            ])
            ->action(function ($record, array $data): void {

                if (!empty($data['status'])) {
                    $record->update([
                        'status' => $data['status'],
                    ]);
                }

                if (!empty($data['payment_status']) && $record->transactionPayment) {
                    $record->transactionPayment->update([
                        'status' => $data['payment_status'],
                    ]);
                }

                if (!empty($data['shipment_status']) && $record->transactionShipment) {
                    $record->transactionShipment->update([
                        'status' => $data['shipment_status'],
                        'tracking_number' => $data['tracking_number'],
                    ]);
                }
                Notification::make()
                    ->title('Update status transaction successfully')
                    ->body('Status transaction successfully updated.')
                    ->success()
                    ->send();
            })
            ->modalSubmitActionLabel('Simpan');
    }

    public static function print(): Action
    {
        return Action::make('print')
            ->label('Cetak Struk')
            ->icon(Heroicon::Printer)
            ->color('success')
            ->url(fn($record) => route('transactions.print', $record))
            ->openUrlInNewTab();
    }

    public static function invoiceA5(): Action
    {
        return  Action::make('invoice_a5')
            ->label('Invoice A5')
            ->icon(Heroicon::DocumentText)
            ->color('info')
            ->url(fn($record) => route('transactions.invoice', $record) . '?paper=a5')
            ->openUrlInNewTab();
    }

    public static function invoiceA4(): Action
    {
        return Action::make('invoice_a4')
            ->label('Invoice A4')
            ->icon(Heroicon::DocumentText)
            ->color('primary')
            ->url(fn($record) => route('transactions.invoice', $record) . '?paper=a4')
            ->openUrlInNewTab();
    }

    public static function addDownPayment(): Action
    {
        return Action::make('addDownPayment')
            ->label('Pelunasan/Penambahan DP')
            ->icon(Heroicon::Banknotes)
            ->color('warning')
            ->visible(fn($record) => $record->is_down_payment)
            ->modalHeading(fn($record) => "Tambah Down Payment - {$record->transaction_code}")
            ->modalWidth(Width::ExtraLarge)
            ->schema([
                Text::make(function ($record) {
                    $grandTotal   = (float) $record->grand_total;
                    $totalPaid    = (float) $record->transactionDownPayments->sum('amount')
                        + (float) ($record->transactionPayment?->amount ?? 0);
                    $remaining    = max($grandTotal - $totalPaid, 0);
                    $isPaid       = $remaining <= 0;
                    $statusLabel  = $isPaid ? 'Lunas' : 'Belum Lunas';
                    $statusColor  = $isPaid ? 'success' : 'danger';

                    $rows = '
                                    <tr>
                                        <td class="py-1 pr-4 text-gray-600 dark:text-gray-400">Grand Total</td>
                                        <td class="py-1 text-right font-medium">' . e(RupiahHelper::format($grandTotal)) . '</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 pr-4 text-gray-600 dark:text-gray-400">Total Sudah Dibayar</td>
                                        <td class="py-1 text-right font-medium">' . e(RupiahHelper::format($totalPaid)) . '</td>
                                    </tr>
                                    <tr class="border-t border-gray-200 dark:border-gray-700">
                                        <td class="py-1 pr-4 font-semibold">Sisa Pembayaran</td>
                                        <td class="py-1 text-right font-semibold ' . ($isPaid ? 'text-success-600' : 'text-danger-600') . '">'
                        . e(RupiahHelper::format($remaining)) . '
                                        </td>
                                    </tr>
                                ';

                    $html = '
                                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                                        <table class="w-full text-sm">
                                            ' . $rows . '
                                        </table>
                                        <div class="mt-2">
                                            <span class="inline-flex items-center fi-badge fi-size-sm font-medium text-' . $statusColor . '-600 ring-1 ring-inset ring-' . $statusColor . '-600/20">
                                                ' . $statusLabel . '
                                            </span>
                                        </div>
                                    </div>
                                ';

                    return new HtmlString($html);
                })
                    ->columnSpanFull(),

                Grid::make(2)
                    ->schema([
                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->columnSpanFull()
                            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                            ->dehydrateStateUsing(
                                fn($state) => $state
                                    ? (float) str_replace('.', '', $state)
                                    : null
                            )
                            ->formatStateUsing(
                                fn($state) => $state
                                    ? number_format((float) $state, 0, ',', '.')
                                    : null
                            )
                            ->default(function ($record) {
                                $grandTotal = (float) $record->grand_total;
                                $totalPaid  = (float) $record->transactionDownPayments->sum('amount')
                                    + (float) ($record->transactionPayment?->amount ?? 0);
                                $remaining  = max($grandTotal - $totalPaid, 0);

                                return $remaining > 0 ? $remaining : null;
                            })
                            ->required()
                            ->prefix('Rp.')
                            ->rules([
                                fn($record) => function (string $attribute, $value, \Closure $fail) use ($record) {
                                    $grandTotal = (float) $record->grand_total;
                                    $totalPaid  = (float) $record->transactionDownPayments->sum('amount')
                                        + (float) ($record->transactionPayment?->amount ?? 0);
                                    $remaining  = $grandTotal - $totalPaid;

                                    if ((float) $value > $remaining) {
                                        $fail('Jumlah melebihi sisa pembayaran (' . RupiahHelper::format($remaining) . ').');
                                    }
                                },
                            ]),

                        Select::make('method_payment')
                            ->label('Metode Pembayaran')
                            ->options(
                                collect(PaymentMethodDpEnum::cases())
                                    ->mapWithKeys(
                                        fn($case) => [$case->value => $case->getLabel()]
                                    )
                                    ->toArray()
                            )
                            ->searchable()
                            ->required()
                            ->native(false),

                        DatePicker::make('paid_at')
                            ->label('Tanggal Bayar')
                            ->native(false)
                            ->suffixIcon(Heroicon::Calendar)
                            ->closeOnDateSelection()
                            ->default(now())
                            ->required(),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ])
            ->action(function (Transaction $record, array $data) {
                $record->transactionDownPayments()->create([
                    'amount'         => $data['amount'],
                    'method_payment' => $data['method_payment'],
                    'paid_at'        => $data['paid_at'],
                    'notes'          => $data['notes'],
                ]);

                Notification::make()
                    ->title('Down Payment berhasil ditambahkan')
                    ->success()
                    ->send();
            })
            ->modalSubmitActionLabel('Simpan');
    }

    public static function sendWhatsapp(): Action
    {
        return Action::make('sendWhatsapp')
            ->label('Kirim WA')
            ->icon(Heroicon::ChatBubbleLeftRight)
            ->color('success')
            ->url(fn(Transaction $record) => static::buildWhatsappUrl($record))
            ->openUrlInNewTab();
    }

    protected static function buildWhatsappUrl(Transaction $record): string
    {
        $customer = $record->customer;
        $phone    = static::formatPhoneNumber($customer?->phone);

        $orderLines = $record->transactionItems
            ->map(function ($item) {
                // Produk biasa
                if (!$item->bundle_id) {
                    $note = $item->note_product
                        ? " ({$item->note_product})"
                        : '';
                    return "{$item->qty}x {$item->product?->name}{$note}";
                }

                // Bundle
                $lines = [];
                $lines[] = "{$item->qty}x {$item->bundle?->name}";
                foreach ($item->bundle->bundleItems as $bundleItem) {
                    $qty = $bundleItem->qty * $item->qty;
                    $lines[] = "   • {$qty}x {$bundleItem->product?->name}";
                }

                if ($item->note_product) {
                    $lines[] = "   Catatan: {$item->note_product}";
                }
                return implode("\n", $lines);
            })
            ->implode("\n\n");

        $itemTotals = $record->transactionItems
            ->map(fn($item) => RupiahHelper::format($item->subtotal))
            ->implode(' + ');

        $totalPaid = (float) $record->transactionDownPayments->sum('amount')
            + (float) ($record->transactionPayment?->amount ?? 0);

        $remaining = max((float) $record->grand_total - $totalPaid, 0);

        $lastDp = $record->transactionDownPayments->sortByDesc('paid_at')->first();

        $message  = "Thank you for your order\n\n";
        $message .= "Nama : {$customer?->name}\n";
        $message .= "Alamat : {$customer?->address}\n";
        $message .= "No Hp : {$customer?->phone}\n\n";
        $message .= "Order :\n{$orderLines}\n\n";
        $message .= "Total: {$itemTotals} = " . RupiahHelper::format($record->grand_total) . "\n\n";
        $message .= "Ongkir: " . ($record->shiping_cost > 0 ? RupiahHelper::format($record->shiping_cost) : '-') . "\n\n";
        $message .= "Pembayaran :\n";
        $message .= "DP transfer: " . RupiahHelper::format($lastDp->amount ?? $totalPaid) . "\n";
        $message .= "Sisa: " . RupiahHelper::format($remaining) . "\n";

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($message);
    }

    protected static function formatPhoneNumber(?string $phone): string
    {
        if (!$phone) {
            return '';
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    public static function edit(): Action
    {
        return \Filament\Actions\EditAction::make();
    }

    public static function delete(): Action
    {
        return \Filament\Actions\DeleteAction::make();
    }

    public static function restore(): Action
    {
        return \Filament\Actions\RestoreAction::make();
    }

    public static function forceDelete(): Action
    {
        return \Filament\Actions\ForceDeleteAction::make();
    }
}
