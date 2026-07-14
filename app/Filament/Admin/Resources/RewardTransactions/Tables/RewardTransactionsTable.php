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
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'earn' => 'Poin Masuk',
                        'redeem' => 'Tukar Reward',
                        'adjustment' => 'Penyesuaian',
                        'bonus' => 'Bonus',
                        default => ucfirst($state),
                    })
                    ->sortable(),
                TextColumn::make('points')
                    ->label('Poin')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($state): string => (int) $state >= 0 ? 'success' : 'danger'),
                TextColumn::make('balance_after')
                    ->label('Saldo Setelah')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('reference')
                    ->label('Referensi')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'earn' => 'Poin Masuk',
                        'redeem' => 'Tukar Reward',
                        'adjustment' => 'Penyesuaian',
                        'bonus' => 'Bonus',
                    ]),
            ]);
    }
}
