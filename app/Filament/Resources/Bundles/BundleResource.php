<?php

namespace App\Filament\Resources\Bundles;

use App\Filament\Resources\Bundles\Pages\CreateBundle;
use App\Filament\Resources\Bundles\Pages\EditBundle;
use App\Filament\Resources\Bundles\Pages\ListBundles;
use App\Filament\Resources\Bundles\Schemas\BundleForm;
use App\Filament\Resources\Bundles\Tables\BundlesTable;
use App\Helpers\RupiahHelper;
use App\Models\Bundle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class BundleResource extends Resource
{
    protected static ?string $model = Bundle::class;
    protected static ?string $navigationLabel = 'Bundles';
    protected static ?string $pluralLabel = 'Bundles';
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Bundle Price' => RupiahHelper::format($record->bundle_price),
            'Status' => $record->is_active ? 'Active' : 'Inactive',
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $storeId = Auth::user()?->store_setting_id;

        if ($storeId) {
            $query->whereHas('storeSetting', function ($q) use ($storeId) {
                $q->where('id', $storeId);
            });
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return BundleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BundlesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBundles::route('/'),
            'create' => CreateBundle::route('/create'),
            'edit' => EditBundle::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
