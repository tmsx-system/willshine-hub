<?php

namespace App\Filament\Admin\Resources\CustomerProductCatalogs;

use App\Filament\Admin\Resources\Concerns\HasResourceNavigationBadge;
use App\Filament\Admin\Resources\CustomerProductCatalogs\Pages\CreateCustomerProductCatalog;
use App\Filament\Admin\Resources\CustomerProductCatalogs\Pages\EditCustomerProductCatalog;
use App\Filament\Admin\Resources\CustomerProductCatalogs\Pages\ListCustomerProductCatalogs;
use App\Filament\Admin\Resources\CustomerProductCatalogs\Schemas\CustomerProductCatalogForm;
use App\Filament\Admin\Resources\CustomerProductCatalogs\Tables\CustomerProductCatalogsTable;
use App\Models\CustomerProductCatalog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerProductCatalogResource extends Resource
{
    use HasResourceNavigationBadge;

    protected static ?string $model = CustomerProductCatalog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static \UnitEnum|string|null $navigationGroup = 'Pelanggan';

    protected static ?int $navigationSort = 22;

    protected static ?string $navigationLabel = 'Aturan Produk Pelanggan';

    protected static ?string $modelLabel = 'Aturan Produk Pelanggan';

    protected static ?string $pluralModelLabel = 'Aturan Produk Pelanggan';

    public static function form(Schema $schema): Schema
    {
        return CustomerProductCatalogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerProductCatalogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomerProductCatalogs::route('/'),
            'create' => CreateCustomerProductCatalog::route('/create'),
            'edit' => EditCustomerProductCatalog::route('/{record}/edit'),
        ];
    }
}
