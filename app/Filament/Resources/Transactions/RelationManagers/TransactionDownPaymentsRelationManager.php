<?php

namespace App\Filament\Resources\Transactions\RelationManagers;

use App\Helpers\RupiahHelper;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionDownPaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactionDownPayments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                            ->dehydrateStateUsing(fn($state) => $state ? (float) str_replace('.', '', $state) : null)
                            ->formatStateUsing(fn($state) => $state ? number_format((float) $state, 0, ',', '.') : null)
                            ->required()
                            ->prefix('Rp.')
                            ->columns(1),
                        DatePicker::make('paid_at')
                            ->label('Tanggal Bayar')
                            ->native(false)
                            ->suffixIcon(Heroicon::Calendar)
                            ->closeOnDateSelection()
                            ->required()
                            ->default(now())
                            ->columns(1),
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->default(null)
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Down Payments')
            ->recordTitleAttribute('transaction')
            ->columns([
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->sortable()
                    ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
                TextColumn::make('paid_at')
                    ->label('Tanggal Bayar')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Notes')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Down Payment')
                    ->modalHeading('Tambahkan Down Payment')
                    ->icon(Heroicon::Plus),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
