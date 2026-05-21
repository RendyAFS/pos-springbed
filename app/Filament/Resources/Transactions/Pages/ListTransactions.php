<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Enums\TransactionStatusEnum;
use App\Filament\Resources\Transactions\Tables\TransactionsTable;
use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggleView')
                ->label(fn () => $this->viewMode === 'kanban'
                    ? 'Table View'
                    : 'Kanban View')
                ->icon(fn () => $this->viewMode === 'kanban'
                    ? Heroicon::Bars3BottomLeft
                    : Heroicon::ViewColumns)
                ->color('gray')
                ->action(function () {
                    $this->viewMode = $this->viewMode === 'kanban'
                        ? 'table'
                        : 'kanban';
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
            ->cardDescription(fn($record) => $record->customer?->name ?? '—')
            ->searchable(['transaction_code', 'customer.name'])
            ->collapsible()
            ->columnWidth('300px');
    }

    public function table(Table $table): Table
    {
        return TransactionsTable::configure($table);
    }
}
