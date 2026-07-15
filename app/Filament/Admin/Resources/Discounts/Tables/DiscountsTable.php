<?php

namespace App\Filament\Admin\Resources\Discounts\Tables;

use App\Filament\Admin\Resources\Concerns\HasDateRangeFilters;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DiscountsTable
{
    use HasDateRangeFilters;

    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Diskon')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->placeholder('Otomatis'),
                TextColumn::make('discount_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'fixed' => 'Nominal',
                        default => 'Persentase',
                    }),
                TextColumn::make('discount_value')
                    ->label('Nilai')
                    ->formatStateUsing(fn ($state, $record): string => $record->discount_type === 'percentage'
                        ? rtrim(rtrim(number_format((float) $state, 2, ',', '.'), '0'), ',') . '%'
                        : 'Rp ' . number_format((float) $state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('scope')
                    ->label('Target')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'product' => 'Produk',
                        'category' => 'Kategori',
                        'customer_type' => 'Tipe Pelanggan',
                        'customer' => 'Pelanggan',
                        default => 'Semua Order',
                    }),
                TextColumn::make('minimum_order_amount')
                    ->label('Min Order')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('maximum_discount_amount')
                    ->label('Max Diskon')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('used_count')
                    ->label('Terpakai')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->label('Mulai')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->label('Berakhir')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('discount_type')
                    ->label('Jenis Diskon')
                    ->options([
                        'percentage' => 'Persentase',
                        'fixed' => 'Nominal Tetap',
                    ]),
                SelectFilter::make('scope')
                    ->label('Target')
                    ->options([
                        'order' => 'Semua Order',
                        'product' => 'Produk Tertentu',
                        'category' => 'Kategori Produk',
                        'customer_type' => 'Tipe Pelanggan',
                        'customer' => 'Akun Pelanggan',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
                self::dateRangeFilter('starts_at', 'Tanggal Mulai'),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                EditAction::make()->label('Ubah'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Terpilih'),
                ]),
            ]);
    }
}
