<?php

namespace App\Filament\Admin\Resources\ErpItems;

use App\Filament\Admin\Resources\Concerns\HasResourceNavigationBadge;
use App\Filament\Admin\Resources\ErpItems\Pages\CreateErpItem;
use App\Filament\Admin\Resources\ErpItems\Pages\EditErpItem;
use App\Filament\Admin\Resources\ErpItems\Pages\ListErpItems;
use App\Filament\Admin\Resources\ErpItems\Schemas\ErpItemForm;
use App\Filament\Admin\Resources\ErpItems\Tables\ErpItemsTable;
use App\Models\ErpItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ErpItemResource extends Resource
{
    use HasResourceNavigationBadge;

    protected static ?string $model = ErpItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static \UnitEnum|string|null $navigationGroup = 'Produk';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Item';

    protected static ?string $modelLabel = 'Item';

    protected static ?string $pluralModelLabel = 'Item';

    public static function form(Schema $schema): Schema
    {
        return ErpItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ErpItemsTable::configure($table);
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
            'index' => ListErpItems::route('/'),
            'create' => CreateErpItem::route('/create'),
            'edit' => EditErpItem::route('/{record}/edit'),
        ];
    }
}
