<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Enums\PaymentMethodDpEnum;
use App\Enums\TransactionPaymentStatusEnum;
use App\Enums\TransactionStatusEnum;
use App\Exports\TransactionsExport;
use App\Filament\Resources\Transactions\Tables\TransactionsTable;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Helpers\RupiahHelper;
use Carbon\Carbon;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use Wezlo\FilamentKanban\Concerns\HasKanbanBoard;
use Wezlo\FilamentKanban\KanbanBoard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Text;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Url;
use App\Filament\Resources\Transactions\Support\TransactionActions;

class ListTransactions extends ListRecords
{
    use HasKanbanBoard;

    protected static string $resource = TransactionResource::class;

    protected string $view = 'filament.pages.transactions.list-transactions';

    #[Url]
    public string $viewMode = 'kanban';
    #[Url]
    public ?string $date_from = null;
    #[Url]
    public ?string $date_until = null;

    public function mount(): void
    {
        parent::mount();

        $this->viewMode = session('transactions_view_mode', 'kanban');

        if (! request()->has('date_from') || ! request()->has('date_until')) {

            $this->redirect(
                static::getResource()::getUrl('index', [
                    'viewMode'   => $this->viewMode,
                    'date_from'  => Carbon::now()->startOfMonth()->toDateString(),
                    'date_until' => Carbon::now()->endOfMonth()->toDateString(),
                ]),
                navigate: true,
            );

            return;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('updateAllStatus')
                ->label('')
                ->color('gray')
                ->extraAttributes([
                    'class' => '!bg-transparent !shadow-none !ring-0 hover:!bg-transparent !px-0 cursor-default pointer-events-none',
                ])
                ->record(fn(array $arguments) => Transaction::find($arguments['record'] ?? null))
                ->visible(function (array $arguments) {
                    $record = Transaction::find($arguments['record'] ?? null);
                    return $record !== null;
                })
                ->modalHeading(fn($record) => "Update Status Transaction - {$record->transaction_code}")
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

                    if (!empty($data['tracking_number']) && $record->transactionShipment) {
                        $record->transactionShipment->update([
                            'tracking_number' => $data['tracking_number'],
                        ]);
                    }

                    Notification::make()
                        ->title('Update status transaction successfully')
                        ->body('Status transaction successfully updated.')
                        ->success()
                        ->send();
                })
                ->modalSubmitActionLabel('Simpan'),
            Action::make('addDownPayment')
                ->label('')
                ->color('gray')
                ->extraAttributes([
                    'class' => '!bg-transparent !shadow-none !ring-0 hover:!bg-transparent !px-0 text-success-600 dark:text-success-400 cursor-default pointer-events-none',
                ])
                ->record(fn(array $arguments) => Transaction::find($arguments['record'] ?? null))
                ->visible(function (array $arguments) {
                    $record = Transaction::find($arguments['record'] ?? null);
                    return $record?->is_down_payment ?? false;
                })
                ->modalHeading(fn($record) => "Tambah Down Payment - {$record->transaction_code}")
                ->modalWidth(Width::ExtraLarge)
                ->schema([
                    Text::make(function ($record) {
                        $grandTotal  = (float) $record->grand_total;
                        $totalPaid   = (float) $record->transactionDownPayments->sum('amount')
                            + (float) ($record->transactionPayment?->amount ?? 0);
                        $remaining   = max($grandTotal - $totalPaid, 0);
                        $isPaid      = $remaining <= 0;
                        $statusLabel = $isPaid ? 'Lunas' : 'Belum Lunas';
                        $statusColor = $isPaid ? 'success' : 'danger';

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
                                        <table class="w-full text-sm">' . $rows . '</table>
                                        <div class="mt-2">
                                            <span class="inline-flex items-center fi-badge fi-size-sm font-medium text-' . $statusColor . '-600 ring-1 ring-inset ring-' . $statusColor . '-600/20">
                                                ' . $statusLabel . '
                                            </span>
                                        </div>
                                    </div>
                                ';

                        return new HtmlString($html);
                    })->columnSpanFull(),

                    Grid::make(2)->schema([
                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->columnSpanFull()
                            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                            ->dehydrateStateUsing(fn($state) => $state ? (float) str_replace('.', '', $state) : null)
                            ->formatStateUsing(fn($state) => $state ? number_format((float) $state, 0, ',', '.') : null)
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
                                    ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
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
                ->action(function (array $data, $record) {
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
                ->modalSubmitActionLabel('Simpan'),
            Action::make('filterDate')
                ->label('Date Range')
                ->icon(Heroicon::Calendar)
                ->color('gray')
                ->fillForm([
                    'date_from' => $this->date_from,
                    'date_until' => $this->date_until,
                ])
                ->schema([
                    Grid::make(2)
                        ->schema([
                            DatePicker::make('date_from')
                                ->label('Dari Tanggal')
                                ->native(false)
                                ->suffixIcon(Heroicon::Calendar)
                                ->closeOnDateSelection(),
                            DatePicker::make('date_until')
                                ->label('Sampai Tanggal')
                                ->native(false)
                                ->suffixIcon(Heroicon::Calendar)
                                ->closeOnDateSelection(),
                        ])
                ])
                ->action(function (array $data) {
                    $this->date_from = $data['date_from'];
                    $this->date_until = $data['date_until'];
                })
                ->modalWidth(Width::ExtraLarge),
            Action::make('toggleView')
                ->label(fn() => $this->viewMode === 'kanban'
                    ? 'Table View'
                    : 'Kanban View')
                ->icon(fn() => $this->viewMode === 'kanban'
                    ? Heroicon::Bars3BottomLeft
                    : Heroicon::ViewColumns)
                ->color('gray')
                ->action(function () {
                    $this->viewMode = $this->viewMode === 'kanban'
                        ? 'table'
                        : 'kanban';

                    session(['transactions_view_mode' => $this->viewMode]);
                }),
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon(Heroicon::ArrowDownTray)
                ->color('success')
                ->fillForm([
                    'export_date_from'  => $this->date_from,
                    'export_date_until' => $this->date_until,
                ])
                ->schema([
                    Grid::make(2)
                        ->schema([
                            DatePicker::make('export_date_from')
                                ->label('Dari Tanggal')
                                ->native(false)
                                ->suffixIcon(Heroicon::Calendar)
                                ->closeOnDateSelection()
                                ->required(),
                            DatePicker::make('export_date_until')
                                ->label('Sampai Tanggal')
                                ->native(false)
                                ->suffixIcon(Heroicon::Calendar)
                                ->closeOnDateSelection()
                                ->required()
                                ->afterOrEqual('export_date_from'),
                        ])
                ])
                ->action(function (array $data) {
                    return Excel::download(
                        new TransactionsExport($data['export_date_from'], $data['export_date_until']),
                        'transaksi-' . $data['export_date_from'] . '-sd-' . $data['export_date_until'] . '.xlsx'
                    );
                })
                ->modalWidth(Width::Large)
                ->modalHeading('Export Transaksi')
                ->modalSubmitActionLabel('Export'),
            CreateAction::make()
                ->label('Tambah Transaksi'),
        ];
    }

    public function kanban(KanbanBoard $kanban): KanbanBoard
    {
        return $kanban
            ->enumColumn('status', TransactionStatusEnum::class)
            ->cardTitle(fn($record) => $record->transaction_code)
            ->cardDescription(fn($record) => $this->buildCardDescription($record))
            ->cardBadges(fn($record) => $this->buildCardBadges($record))
            ->searchable(['transaction_code', 'customer.name'])
            ->collapsible()
            ->columnWidth('360px')
            ->columnColor(fn($column) => match ($column->value ?? $column) {
                TransactionStatusEnum::PENDING->value   => 'gray',
                TransactionStatusEnum::PROCESSED->value => 'warning',
                TransactionStatusEnum::SHIPPED->value   => 'info',
                TransactionStatusEnum::DELIVERED->value => 'success',
                TransactionStatusEnum::CANCELLED->value => 'danger',
                default                                 => 'gray',
            })
            ->cardFooterActions([
                ...TransactionActions::kanbanIcons(),
                ...TransactionActions::kanbanDropdown(),
            ])
            ->modifyQueryUsing(function ($query) {
                $filters = $this->kanbanFilters ?? [];

                $query->withoutTrashed();

                if (!empty($filters['payment_status'])) {
                    $query->whereHas(
                        'transactionPayment',
                        fn($q) => $q->where('status', $filters['payment_status'])
                    );
                }

                if (!empty($filters['shipment_status'])) {
                    $query->whereHas(
                        'transactionShipment',
                        fn($q) => $q->where('status', $filters['shipment_status'])
                    );
                }

                if ($this->date_from) {
                    $query->whereDate('created_at', '>=', $this->date_from);
                }

                if ($this->date_until) {
                    $query->whereDate('created_at', '<=', $this->date_until);
                }

                return $query;
            });
    }

    private function buildCardDescription(Transaction $record): string
    {
        Carbon::setLocale('id');

        $lines = [];

        $customerName  = $record->customer?->name ?? '-';
        $customerPhone = $record->customer?->phone;

        $date = $record->transaction_date
            ? Carbon::parse($record->transaction_date)->translatedFormat('d F Y')
            : '-';

        $amount = $record->transactionPayment?->amount
            ?? $record->grand_total
            ?? 0;

        $lines[] = $customerName
            . ($customerPhone ? " • {$customerPhone}" : '');

        $lines[] = "Tanggal Transaksi: {$date}";

        if ($record->is_down_payment && $record->transactionDownPayments?->isNotEmpty()) {
            $lastDp     = $record->transactionDownPayments->sortByDesc('paid_at')->first();
            $lastDpDate = $lastDp->paid_at
                ? Carbon::parse($lastDp->paid_at)->translatedFormat('d F Y')
                : '-';
            $lines[] = "DP Terakhir: {$lastDpDate}";
        }

        $lines[] = "Total: " . RupiahHelper::format($amount);

        if ($record->is_down_payment) {
            $totalDP = $record->transactionDownPayments?->sum('amount')
                + (float) ($record->transactionPayment?->amount ?? 0);

            $remaining = (float) $record->grand_total - $totalDP;

            if ($remaining > 0) {
                $lines[] = "Sisa Pembayaran: " . RupiahHelper::format($remaining);
            } else {
                $lines[] = "Pembayaran: DP";
            }
        }

        $courier  = $record->transactionShipment?->courier?->name;
        $tracking = $record->transactionShipment?->tracking_number;

        if ($courier) {
            $delivery = "Pengiriman: {$courier}";
            if ($tracking) {
                $delivery .= " • {$tracking}";
            }
            $lines[] = $delivery;
        }

        $hasPreOrder = $record->transactionItems
            ?->where('is_pre_order', true)
            ->isNotEmpty();

        if ($hasPreOrder) {
            $lines[] = "Tipe Order: Pre Order";
        }

        return implode("\n", $lines);
    }

    private function buildCardBadges(Transaction $record): array
    {
        Carbon::setLocale('id');

        $badges = [];

        // Badge Lunas / Belum Lunas
        $paymentAmount = (float) ($record->transactionPayment?->amount ?? 0);
        $dpAmount = (float) ($record->transactionDownPayments?->sum('amount') ?? 0);
        $grandTotal = (float) $record->grand_total;

        $totalPaid = $record->is_down_payment
            ? $paymentAmount + $dpAmount
            : $paymentAmount;

        $isPaidOff = $totalPaid >= $grandTotal;

        $badges[] = [
            'label' => $isPaidOff ? 'Lunas' : 'Belum Lunas',
            'color' => $isPaidOff ? 'success' : 'warning',
        ];

        // Badge Status Pembayaran
        $paymentStatus = $record->transactionPayment?->status;
        if ($paymentStatus instanceof TransactionPaymentStatusEnum) {
            $badges[] = [
                'label' => match ($paymentStatus) {
                    TransactionPaymentStatusEnum::PENDING => 'Pending Payment',
                    TransactionPaymentStatusEnum::FAILED  => 'Failed Payment',
                    default => $paymentStatus->getLabel(),
                },
                'color' => match ($paymentStatus) {
                    TransactionPaymentStatusEnum::PAID    => 'success',
                    TransactionPaymentStatusEnum::PENDING => 'warning',
                    TransactionPaymentStatusEnum::FAILED  => 'danger',
                },
            ];

            if ($paymentStatus === TransactionPaymentStatusEnum::PAID) {
                $paidAt = $record->transactionPayment?->paid_at;

                $badges[count($badges) - 1]['label'] .= $paidAt
                    ? "\n" . Carbon::parse($paidAt)->translatedFormat('d F Y')
                    : '';
            }
        }

        // Badge Dikirim / Belum Dikirim
        if ($record->status === TransactionStatusEnum::DELIVERED) {

            $deliveredAt = $record->updated_at;

            $badges[] = [
                'label' => TransactionStatusEnum::DELIVERED->getLabel()
                    . ($deliveredAt
                        ? "\n" . Carbon::parse($deliveredAt)->translatedFormat('d F Y')
                        : ''),
                'color' => 'success',
            ];
        }

        return $badges;
    }

    public function table(Table $table): Table
    {
        return TransactionsTable::configure($table)
            ->modifyQueryUsing(function ($query) {

                if ($this->date_from) {
                    $query->whereDate('created_at', '>=', $this->date_from);
                }

                if ($this->date_until) {
                    $query->whereDate('created_at', '<=', $this->date_until);
                }

                return $query;
            });
    }
}
