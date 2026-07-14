<?php

namespace App\Filament\Admin\Resources\RewardRedemptions;

use App\Filament\Admin\Resources\RewardRedemptions\Pages\ListRewardRedemptions;
use App\Filament\Admin\Resources\RewardRedemptions\Tables\RewardRedemptionsTable;
use App\Models\CustomerAccount;
use App\Models\RewardRedemption;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class RewardRedemptionResource extends Resource
{
    protected static ?string $model = RewardRedemption::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static \UnitEnum|string|null $navigationGroup = 'Rewards';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Reward Redemptions';

    protected static ?string $modelLabel = 'Reward Redemption';

    protected static ?string $pluralModelLabel = 'Reward Redemptions';

    public static function table(Table $table): Table
    {
        return RewardRedemptionsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        if (!Schema::hasTable('reward_redemptions')) {
            return null;
        }

        $count = static::applySalesVisibility(
            RewardRedemption::query()->where('status', 'pending')
        )->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Pending reward redemptions';
    }

    public static function getEloquentQuery(): Builder
    {
        return static::applySalesVisibility(
            parent::getEloquentQuery()->with(['customerAccount.salesPerson', 'reward'])
        );
    }

    protected static function applySalesVisibility(Builder $query): Builder
    {
        $user = auth()->user();

        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        $hasSalesAssignments = CustomerAccount::query()
            ->whereNotNull('sales_user_id')
            ->exists();

        if (!$hasSalesAssignments) {
            return $query;
        }

        $matchedCustomerAccountIds = CustomerAccount::query()
            ->where('sales_user_id', $user->id)
            ->pluck('id');

        if ($matchedCustomerAccountIds->isEmpty()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereIn('customer_account_id', $matchedCustomerAccountIds);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRewardRedemptions::route('/'),
        ];
    }
}
