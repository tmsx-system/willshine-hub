<?php

namespace App\Filament\Admin\Resources\Rewards;

use App\Filament\Admin\Resources\Concerns\HasResourceNavigationBadge;
use App\Filament\Admin\Resources\Rewards\Pages\CreateReward;
use App\Filament\Admin\Resources\Rewards\Pages\EditReward;
use App\Filament\Admin\Resources\Rewards\Pages\ListRewards;
use App\Filament\Admin\Resources\Rewards\Schemas\RewardForm;
use App\Filament\Admin\Resources\Rewards\Tables\RewardsTable;
use App\Models\Reward;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RewardResource extends Resource
{
    use HasResourceNavigationBadge;

    protected static ?string $model = Reward::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    protected static \UnitEnum|string|null $navigationGroup = 'Reward';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Katalog Reward';

    protected static ?string $modelLabel = 'Reward';

    protected static ?string $pluralModelLabel = 'Katalog Reward';

    public static function form(Schema $schema): Schema
    {
        return RewardForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RewardsTable::configure($table);
    }

    protected static function getNavigationBadgeColumn(): ?string
    {
        return 'is_active';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRewards::route('/'),
            'create' => CreateReward::route('/create'),
            'edit' => EditReward::route('/{record}/edit'),
        ];
    }
}
