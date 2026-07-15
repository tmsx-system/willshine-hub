<?php

namespace App\Filament\Admin\Resources\Discounts;

use App\Filament\Admin\Resources\Concerns\HasResourceNavigationBadge;
use App\Filament\Admin\Resources\Discounts\Pages\CreateDiscount;
use App\Filament\Admin\Resources\Discounts\Pages\EditDiscount;
use App\Filament\Admin\Resources\Discounts\Pages\ListDiscounts;
use App\Filament\Admin\Resources\Discounts\Schemas\DiscountForm;
use App\Filament\Admin\Resources\Discounts\Tables\DiscountsTable;
use App\Models\Discount;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DiscountResource extends Resource
{
    use HasResourceNavigationBadge;

    protected static ?string $model = Discount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static \UnitEnum|string|null $navigationGroup = 'Reward';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationLabel = 'Manajemen Diskon';

    protected static ?string $modelLabel = 'Diskon';

    protected static ?string $pluralModelLabel = 'Manajemen Diskon';

    public static function form(Schema $schema): Schema
    {
        return DiscountForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiscountsTable::configure($table);
    }

    protected static function getNavigationBadgeColumn(): ?string
    {
        return 'is_active';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDiscounts::route('/'),
            'create' => CreateDiscount::route('/create'),
            'edit' => EditDiscount::route('/{record}/edit'),
        ];
    }
}
