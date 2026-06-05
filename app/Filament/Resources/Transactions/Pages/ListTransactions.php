<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Enums\TransactionPaymentStatusEnum;
use App\Enums\TransactionStatusEnum;
use App\Filament\Resources\Transactions\Tables\TransactionsTable;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Helpers\RupiahHelper;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Wezlo\FilamentKanban\Concerns\HasKanbanBoard;
use Wezlo\FilamentKanban\KanbanBoard;

class ListTransactions extends ListRecords
{
    use HasKanbanBoard;

    protected static string $resource = TransactionResource::class;

    protected string $view = 'filament.pages.transactions.list-transactions';

    public string $viewMode = 'kanban';
    public ?string $date_from = null;
    public ?string $date_until = null;

    public function mount(): void
    {
        parent::mount();

        $this->viewMode = (string) session('transactions_view_mode', 'kanban');
        $this->date_from = Carbon::now()->startOfMonth()->toDateString();
        $this->date_until = Carbon::now()->endOfMonth()->toDateString();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('filterDate')
                ->label('Filter')
                ->icon(Heroicon::Funnel)
                ->color('gray')
                ->visible(fn() => $this->viewMode === 'kanban')
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
                Action::make('view')
                    ->icon(Heroicon::Eye)
                    ->url(fn($record) => TransactionResource::getUrl('view', ['record' => $record])),

                Action::make('edit')
                    ->icon(Heroicon::PencilSquare)
                    ->url(fn($record) => TransactionResource::getUrl('edit', ['record' => $record])),
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

        $paymentStatus = $record->transactionPayment?->status;
        if ($paymentStatus instanceof TransactionPaymentStatusEnum) {
            $badges[] = [
                'label' => $paymentStatus->getLabel(),
                'color' => match ($paymentStatus) {
                    TransactionPaymentStatusEnum::PAID    => 'success',
                    TransactionPaymentStatusEnum::PENDING => 'warning',
                    TransactionPaymentStatusEnum::FAILED  => 'danger',
                },
            ];

            if ($paymentStatus === TransactionPaymentStatusEnum::PAID) {
                $paidAt = $record->transactionPayment?->paid_at;
                if ($paidAt) {
                    $badges[] = [
                        'label' => Carbon::parse($paidAt)->translatedFormat('d F Y'),
                        'color' => 'gray',
                    ];
                }
            }
        }

        if ($record->status === TransactionStatusEnum::DELIVERED) {
            $badges[] = [
                'label' => TransactionStatusEnum::DELIVERED->getLabel(),
                'color' => 'success',
            ];

            $deliveredAt = $record->transactionShipment?->updated_at;
            if ($deliveredAt) {
                $badges[] = [
                    'label' => Carbon::parse($deliveredAt)->translatedFormat('d F Y'),
                    'color' => 'gray',
                ];
            }
        }

        return $badges;
    }

    public function table(Table $table): Table
    {
        return TransactionsTable::configure($table);
    }
}
