<?php

namespace App\Filament\Admin\Resources\CustomerProductCatalogs\Tables;

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

class CustomerProductCatalogsTable
{
    use HasDateRangeFilters;

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.customer_code')
                    ->label('Kode Pelanggan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.customer_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('productCatalog.item_code')
                    ->label('Kode Item')
                    ->searchable(),
                TextColumn::make('productCatalog.display_name')
                    ->label('Produk')
                    ->searchable(),
                TextColumn::make('daily_quantity')
                    ->label('Alokasi Harian')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('minimum_qty')
                    ->label('Min Order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('maximum_qty')
                    ->label('Max Order')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('customer_id')
                    ->label('Pelanggan')
                    ->relationship('customer', 'customer_name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('product_catalog_id')
                    ->label('Katalog Produk')
                    ->relationship('productCatalog', 'display_name')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('is_active')
                    ->label('Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
                self::dateRangeFilter('updated_at', 'Tanggal Diubah'),
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
