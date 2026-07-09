<?php

namespace App\Filament\Admin\Resources\ProductCatalogs;

use App\Filament\Admin\Resources\ProductCatalogs\Pages\CreateProductCatalog;
use App\Filament\Admin\Resources\ProductCatalogs\Pages\EditProductCatalog;
use App\Filament\Admin\Resources\ProductCatalogs\Pages\ListProductCatalogs;
use App\Filament\Admin\Resources\ProductCatalogs\Schemas\ProductCatalogForm;
use App\Filament\Admin\Resources\ProductCatalogs\Tables\ProductCatalogsTable;
use App\Models\ProductCatalog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductCatalogResource extends Resource
{
    protected static ?string $model = ProductCatalog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProductCatalogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductCatalogsTable::configure($table);
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
            'index' => ListProductCatalogs::route('/'),
            'create' => CreateProductCatalog::route('/create'),
            'edit' => EditProductCatalog::route('/{record}/edit'),
        ];
    }
}
