<?php

namespace App\Filament\Admin\Resources\RewardTransactions\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RewardTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('customerAccount.customer_name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                TextColumn::make('points')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($state): string => (int) $state >= 0 ? 'success' : 'danger'),
                TextColumn::make('balance_after')
                    ->label('Balance After')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reference')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('description')
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'earn' => 'Earn',
                        'redeem' => 'Redeem',
                        'adjustment' => 'Adjustment',
                        'bonus' => 'Bonus',
                    ]),
            ]);
    }
}
