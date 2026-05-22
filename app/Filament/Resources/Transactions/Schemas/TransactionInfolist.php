<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Enums\TransactionPaymentMethodEnum;
use App\Enums\TransactionPaymentStatusEnum;
use App\Enums\TransactionStatusEnum;
use App\Helpers\RupiahHelper;
use Carbon\Carbon;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Info Transaksi')
                            ->icon(Heroicon::DocumentText)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('transaction_code')
                                    ->label('Kode Transaksi')
                                    ->copyable()
                                    ->badge()
                                    ->color('primary'),
                                TextEntry::make('transaction_date')
                                    ->label('Tanggal Transaksi')
                                    ->date('d M Y'),
                                TextEntry::make('customer.name')
                                    ->label('Customer')
                                    ->icon(Heroicon::User),
                                TextEntry::make('customer.phone')
                                    ->label('Telepon')
                                    ->icon(Heroicon::Phone)
                                    ->placeholder('—'),
                                TextEntry::make('customer.address')
                                    ->label('Alamat Customer')
                                    ->icon(Heroicon::MapPin)
                                    ->placeholder('—')
                                    ->columnSpanFull(),
                                TextEntry::make('status')
                                    ->label('Status Transaksi')
                                    ->badge()
                                    ->formatStateUsing(fn($state) => $state instanceof TransactionStatusEnum
                                        ? $state->getLabel()
                                        : TransactionStatusEnum::from($state)->getLabel())
                                    ->color(fn($state): string => match ($state instanceof TransactionStatusEnum ? $state : TransactionStatusEnum::from($state)) {
                                        TransactionStatusEnum::PENDING   => 'warning',
                                        TransactionStatusEnum::PROCESSED => 'info',
                                        TransactionStatusEnum::SHIPPED   => 'primary',
                                        TransactionStatusEnum::DELIVERED => 'success',
                                        TransactionStatusEnum::CANCELLED => 'danger',
                                        default                          => 'gray',
                                    }),
                                TextEntry::make('storeSetting.store_name')
                                    ->label('Toko')
                                    ->icon(Heroicon::BuildingStorefront),
                            ]),

                        Section::make('Pembayaran')
                            ->icon(Heroicon::CreditCard)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('grand_total')
                                    ->label('Grand Total')
                                     ->formatStateUsing(fn($state) => RupiahHelper::format($state))
                                    ->weight(FontWeight::Bold)
                                    ->color('success')
                                    ->columnSpanFull(),
                                TextEntry::make('transactionPayment.method')
                                    ->label('Metode Pembayaran')
                                    ->badge()
                                    ->formatStateUsing(fn($state) => $state instanceof TransactionPaymentMethodEnum
                                        ? $state->getLabel()
                                        : ($state ? TransactionPaymentMethodEnum::from($state)->getLabel() : '—')),
                                TextEntry::make('transactionPayment.status')
                                    ->label('Status Pembayaran')
                                    ->badge()
                                    ->formatStateUsing(fn($state) => $state instanceof TransactionPaymentStatusEnum
                                        ? $state->getLabel()
                                        : ($state ? TransactionPaymentStatusEnum::from($state)->getLabel() : '—'))
                                    ->color(fn($state): string => match ($state instanceof TransactionPaymentStatusEnum
                                        ? $state
                                        : ($state ? TransactionPaymentStatusEnum::from($state) : null)) {
                                        TransactionPaymentStatusEnum::PAID    => 'success',
                                        TransactionPaymentStatusEnum::PENDING => 'warning',
                                        TransactionPaymentStatusEnum::FAILED  => 'danger',
                                        default                               => 'gray',
                                    }),
                                TextEntry::make('transactionPayment.amount')
                                    ->label('Jumlah Dibayar')
                                     ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                     ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
                                TextEntry::make('promo_total')
                                    ->label('Diskon Promo')
                                     ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
                                TextEntry::make('shiping_cost')
                                    ->label('Biaya Pengiriman')
                                     ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
                                TextEntry::make('is_down_payment')
                                    ->label('Down Payment')
                                    ->badge()
                                    ->formatStateUsing(fn($state) => $state ? 'Ya' : 'Tidak')
                                    ->color(fn($state) => $state ? 'warning' : 'gray'),
                                TextEntry::make('due_date_down_payment')
                                    ->label('Jatuh Tempo DP')
                                    ->formatStateUsing(fn($state) => $state
                                        ? Carbon::parse($state)->format('d M Y H:i')
                                        : '—'),
                            ]),
                    ]),

                Grid::make(2)
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Item Transaksi')
                            ->icon(Heroicon::ShoppingCart)
                            ->schema([
                                RepeatableEntry::make('transactionItems')
                                    ->label('')
                                    ->columns(3)
                                    ->schema([
                                        TextEntry::make('product.name')
                                            ->label('Produk')
                                            ->columnSpan(2)
                                            ->formatStateUsing(function ($state, $record) {
                                                if ($state) return $state;
                                                return $record->bundle?->name
                                                    ? '[Bundle] ' . $record->bundle->name
                                                    : '—';
                                            }),
                                        TextEntry::make('qty')
                                            ->label('Qty')
                                            ->suffix(' pcs'),
                                        TextEntry::make('selling_price')
                                            ->label('Harga Jual')
                                             ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
                                        TextEntry::make('discount')
                                            ->label('Diskon')
                                             ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
                                        TextEntry::make('subtotal')
                                            ->label('Subtotal')
                                             ->formatStateUsing(fn($state) => RupiahHelper::format($state))
                                            ->weight(FontWeight::Bold)
                                            ->color('primary'),
                                    ]),
                            ]),

                        Section::make('Pengiriman')
                            ->icon(Heroicon::Truck)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('transactionShipment.courier.name')
                                    ->label('Kurir')
                                    ->placeholder('—'),
                                TextEntry::make('transactionShipment.tracking_number')
                                    ->label('No. Resi')
                                    ->copyable()
                                    ->placeholder('—'),
                                TextEntry::make('transactionShipment.status')
                                    ->label('Status Pengiriman')
                                    ->badge()
                                    ->placeholder('—'),
                                TextEntry::make('shiping_cost')
                                    ->label('Biaya Pengiriman')
                                     ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
                            ]),
                    ]),

            ]);
    }
}
