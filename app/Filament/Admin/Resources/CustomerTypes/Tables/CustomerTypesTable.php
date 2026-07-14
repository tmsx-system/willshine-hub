<?php

namespace App\Filament\Admin\Resources\CustomerTypes\Tables;

use App\Filament\Admin\Resources\Concerns\HasDateRangeFilters;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CustomerTypesTable
{
    use HasDateRangeFilters;

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Tipe')
                    ->searchable(),
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),
                TextColumn::make('default_price_list')
                    ->label('Price List Default')
                    ->searchable(),
                TextColumn::make('default_warehouse')
                    ->label('Gudang')
                    ->searchable(),
                TextColumn::make('minimum_order_amount')
                    ->label('Minimal Nilai')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('minimum_order_qty')
                    ->label('Minimal Qty')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('allow_reward')
                    ->label('Reward')
                    ->boolean(),
                IconColumn::make('allow_promo')
                    ->label('Promo')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
                TernaryFilter::make('allow_reward')
                    ->label('Boleh Reward')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
                TernaryFilter::make('allow_promo')
                    ->label('Boleh Promo')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
                self::dateRangeFilter('created_at', 'Tanggal Dibuat'),
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
