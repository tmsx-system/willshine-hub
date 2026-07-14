<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Widgets\Concerns\ScopesDashboardData;
use App\Models\RewardRedemption;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class PendingRewardRedemptions extends TableWidget
{
    use ScopesDashboardData;

    protected static ?int $sort = 50;

    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public static function canView(): bool
    {
        return Schema::hasTable('reward_redemptions');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Reward Menunggu Approval')
            ->description('Pengajuan tukar reward yang perlu diproses.')
            ->query($this->query())
            ->columns([
                TextColumn::make('redemption_number')
                    ->label('No. Pengajuan')
                    ->searchable(),
                TextColumn::make('customerAccount.customer_name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('reward.name')
                    ->label('Reward'),
                TextColumn::make('points_spent')
                    ->label('Poin')
                    ->numeric(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->since(),
            ])
            ->paginated(false);
    }

    private function query(): Builder
    {
        return $this->applyCustomerAccountScope(
            RewardRedemption::query()
                ->with(['customerAccount', 'reward'])
                ->where('status', 'pending')
                ->latest()
                ->limit(6)
        );
    }
}
