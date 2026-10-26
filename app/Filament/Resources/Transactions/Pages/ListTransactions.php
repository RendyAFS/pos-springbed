<?php

namespace App\Filament\Resources\Transactions\Pages;

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
use Filament\Schemas\Components\Grid;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use Wezlo\FilamentKanban\Concerns\HasKanbanBoard;
use Wezlo\FilamentKanban\KanbanBoard;
use Livewire\Attributes\Url;
use App\Filament\Resources\Transactions\Support\TransactionActions;
use App\Models\User;
use Filament\Forms\Components\Select;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;

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
    #[Url]
    public bool $all_staff = false;

    public function mount(): void
    {
        parent::mount();

        $this->viewMode = session('transactions_view_mode', 'kanban');
        $this->all_staff = request()->has('all_staff')
            ? request()->boolean('all_staff')
            : session('transactions_all_staff', false);

        if (! request()->has('date_from') || ! request()->has('date_until')) {

            $this->redirect(
                static::getResource()::getUrl('index', [
                    'viewMode'   => $this->viewMode,
                    'date_from'  => Carbon::now()->startOfMonth()->toDateString(),
                    'date_until' => Carbon::now()->endOfMonth()->toDateString(),
                    'all_staff'  => $this->all_staff ? 1 : 0,
                ]),
                navigate: true,
            );

            return;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            TransactionActions::updateStatus()
                ->label('')
                ->color('gray')
                ->extraAttributes([
                    'class' => 'hidden',
                ]),

            TransactionActions::addDownPayment()
                ->label('')
                ->color('gray')
                ->extraAttributes([
                    'class' => 'hidden',
                ]),

            TransactionActions::verifyTransaction()
                ->label('')
                ->color('gray')
                ->extraAttributes([
                    'class' => 'hidden',
                ]),

            Action::make('filterDate')
                ->label('Periode')
                ->tooltip('Filter Rentang Tanggal')
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
                ->modalWidth(Width::Medium),

            Action::make('toggleAllStaff')
                ->label(fn() => $this->all_staff ? 'Semua Staff' : 'Transaksi Saya')
                ->icon(fn() => $this->all_staff ? Heroicon::UserGroup : Heroicon::User)
                ->color(fn() => $this->all_staff ? 'primary' : 'gray')
                ->outlined(fn() => ! $this->all_staff)
                ->tooltip('Beralih antara Transaksi Saya / Semua Staff di Toko')
                ->visible(function () {
                    /** @var User|null $user */
                    $user = Auth::user();
                    return $user?->isStaff() ?? false;
                })
                ->action(function () {
                    $this->all_staff = ! $this->all_staff;
                    session(['transactions_all_staff' => $this->all_staff]);
                }),

            Action::make('toggleView')
                ->label(fn() => $this->viewMode === 'kanban' ? 'Tabel' : 'Kanban')
                ->tooltip(fn() => $this->viewMode === 'kanban' ? 'Beralih ke Tampilan Tabel' : 'Beralih ke Tampilan Kanban')
                ->icon(fn() => $this->viewMode === 'kanban' ? Heroicon::Bars3BottomLeft : Heroicon::ViewColumns)
                ->color('gray')
                ->action(function () {
                    $this->viewMode = $this->viewMode === 'kanban' ? 'table' : 'kanban';
                    session(['transactions_view_mode' => $this->viewMode]);
                }),

            Action::make('exportExcel')
                ->label('Export')
                ->tooltip('Export Transaksi ke Excel')
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
                            Select::make('created_by')
                                ->label('Created By')
                                ->columnSpanFull()
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->options(fn() => User::whereDoesntHave('roles', fn($q) => $q->whereIn('name', ['Super Admin', 'Owner']))
                                    ->pluck('name', 'id'))
                        ])
                ])
                ->action(function (array $data) {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();

                    $canViewHargaNetto = $user?->can('ViewHargaNettoTransaction') ?? false;

                    return Excel::download(
                        new TransactionsExport(
                            $data['export_date_from'],
                            $data['export_date_until'],
                            $canViewHargaNetto,
                            $data['created_by'] ?? []
                        ),
                        'transaksi-' . $data['export_date_from'] . '-sd-' . $data['export_date_until'] . '.xlsx'
                    );
                })
                ->modalWidth(Width::Medium)
                ->modalHeading('Export Transaksi')
                ->modalSubmitActionLabel('Export'),

            CreateAction::make()
                ->label('Tambah Transaksi'),
        ];
    }

    public function getSubheading(): string|Htmlable|null
    {
        if (! $this->date_from || ! $this->date_until) {
            return null;
        }

        $from  = Carbon::parse($this->date_from)->translatedFormat('d M Y');
        $until = Carbon::parse($this->date_until)->translatedFormat('d M Y');

        /** @var User|null $user */
        $user = Auth::user();
        $staffBadge = '';
        if ($user?->isStaff()) {
            if ($this->all_staff) {
                $staffBadge = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-50 text-primary-700 ring-1 ring-inset ring-primary-700/10 dark:bg-primary-400/10 dark:text-primary-400 dark:ring-primary-400/30 whitespace-nowrap">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    Semua Staff
                </span>';
            } else {
                $staffBadge = '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 ring-1 ring-inset ring-gray-500/10 dark:bg-gray-800 dark:text-gray-400 whitespace-nowrap">
                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    Transaksi Saya
                </span>';
            }
        }

        return new HtmlString(
            '<div class="flex flex-wrap items-center gap-2 pt-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 ring-1 ring-inset ring-gray-600/10 dark:bg-gray-800 dark:text-gray-300 whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    ' . e($from) . ' &ndash; ' . e($until) . '
                </span>
                ' . $staffBadge . '
            </div>'
        );
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
            ->filters([
                Select::make('all_staff')
                    ->label('Tampilkan Staff')
                    ->placeholder('Ikuti Toggle Header')
                    ->options([
                        '0' => 'Hanya Transaksi Saya',
                        '1' => 'Semua Staff di Toko',
                    ])
                    ->visible(function () {
                        /** @var User|null $user */
                        $user = Auth::user();
                        return $user?->isStaff() ?? false;
                    }),
                Select::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options(
                        collect(TransactionPaymentStatusEnum::cases())
                            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                            ->toArray()
                    ),
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

                /** @var User|null $user */
                $user = Auth::user();
                if ($user?->isStaff()) {
                    $kanbanFilterAllStaff = $filters['all_staff'] ?? null;
                    $showAll = filled($kanbanFilterAllStaff)
                        ? (bool) $kanbanFilterAllStaff
                        : $this->all_staff;

                    if (! $showAll) {
                        $query->where('created_by', $user->id);
                    }
                }

                return $query;
            });
    }

    protected function getKanbanQuery(KanbanBoard $board): Builder
    {
        $query = static::getResource()::getEloquentQuery();

        if ($modifier = $board->getQueryModifier()) {
            $query = $modifier($query);
        }

        foreach ($this->kanbanFilters as $attribute => $value) {
            if (filled($value)) {
                if (in_array($attribute, ['all_staff', 'payment_status', 'shipment_status'], true)) {
                    continue;
                }
                $query->where($attribute, $value);
            }
        }

        if (filled($this->kanbanSearch) && ! empty($board->getSearchableColumns())) {
            $search = $this->kanbanSearch;
            $searchableColumns = $board->getSearchableColumns();

            $query->where(function (Builder $q) use ($searchableColumns, $search) {
                foreach ($searchableColumns as $col) {
                    if (str_contains($col, '.')) {
                        $parts = explode('.', $col);
                        $attribute = array_pop($parts);
                        $relation = implode('.', $parts);

                        $q->orWhereHas($relation, function (Builder $relQuery) use ($attribute, $search) {
                            $relQuery->where($attribute, 'like', "%{$search}%");
                        });
                    } else {
                        $q->orWhere($col, 'like', "%{$search}%");
                    }
                }
            });
        }

        return $query;
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

        if ($record->creator?->name) {
            $lines[] = "Staff: {$record->creator->name}";
        }

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

        // Badge Verifikasi Owner
        if ($record->is_verified) {
            $verifier = $record->verifiedBy?->name ?? 'Owner';
            $verifiedDate = $record->verified_at
                ? Carbon::parse($record->verified_at)->translatedFormat('d M Y')
                : '';

            $label = "Verified by {$verifier}";
            if ($verifiedDate) {
                $label .= "\n{$verifiedDate}";
            }

            $badges[] = [
                'label' => $label,
                'color' => 'success',
            ];
        } else {
            $badges[] = [
                'label' => 'Unverified',
                'color' => 'gray',
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

                /** @var User|null $user */
                $user = Auth::user();
                if ($user?->isStaff()) {
                    $tableFilterAllStaff = $this->tableFilters['all_staff']['value'] ?? null;
                    $showAll = $tableFilterAllStaff !== null
                        ? (bool) $tableFilterAllStaff
                        : $this->all_staff;

                    if (! $showAll) {
                        $query->where('created_by', $user->id);
                    }
                }

                return $query;
            });
    }
}
