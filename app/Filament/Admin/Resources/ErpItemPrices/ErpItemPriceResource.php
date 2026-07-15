<?php

namespace App\Filament\Admin\Resources\ErpItemPrices;

use App\Filament\Admin\Resources\ErpItemPrices\Pages\CreateErpItemPrice;
use App\Filament\Admin\Resources\ErpItemPrices\Pages\EditErpItemPrice;
use App\Filament\Admin\Resources\ErpItemPrices\Pages\ListErpItemPrices;
use App\Filament\Admin\Resources\ErpItemPrices\Schemas\ErpItemPriceForm;
use App\Filament\Admin\Resources\ErpItemPrices\Tables\ErpItemPricesTable;
use App\Models\ErpItemPrice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ErpItemPriceResource extends Resource
{
    protected static ?string $model = ErpItemPrice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static \UnitEnum|string|null $navigationGroup = 'Produk';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationLabel = 'Daftar Harga Item';

    protected static ?string $modelLabel = 'Harga Item';

    protected static ?string $pluralModelLabel = 'Daftar Harga Item';

    public static function form(Schema $schema): Schema
    {
        return ErpItemPriceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ErpItemPricesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListErpItemPrices::route('/'),
            'create' => CreateErpItemPrice::route('/create'),
            'edit' => EditErpItemPrice::route('/{record}/edit'),
        ];
    }
}
