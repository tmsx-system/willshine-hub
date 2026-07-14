<?php

namespace App\Filament\Admin\Resources\ProductCatalogs\Tables;

use App\Filament\Admin\Resources\Concerns\HasDateRangeFilters;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductCatalogsTable
{
    use HasDateRangeFilters;

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item.item_code')
                    ->label('ERP Item')
                    ->sortable(),
                TextColumn::make('item_code')
                    ->label('Kode Katalog')
                    ->searchable(),
                TextColumn::make('item_name')
                    ->label('Nama Item Katalog')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('display_name')
                    ->label('Nama Tampil')
                    ->searchable(),
                ImageColumn::make('display_image_url')
                    ->label('Gambar'),
                IconColumn::make('is_visible')
                    ->label('Tampil')
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),
                TextColumn::make('display_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('minimum_qty')
                    ->label('Min Qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('maximum_qty')
                    ->label('Max Qty')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('allow_decimal_qty')
                    ->label('Qty Desimal')
                    ->boolean(),
                IconColumn::make('show_stock')
                    ->label('Tampilkan Stok')
                    ->boolean(),
                IconColumn::make('show_price')
                    ->label('Tampilkan Harga')
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
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('item_group')
                    ->label('Grup Item ERP')
                    ->searchable()
                    ->options(fn (): array => \App\Models\ErpItem::query()
                        ->whereNotNull('item_group')
                        ->distinct()
                        ->orderBy('item_group')
                        ->pluck('item_group', 'item_group')
                        ->all())
                    ->query(fn (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder => $query
                        ->when($data['value'] ?? null, fn (\Illuminate\Database\Eloquent\Builder $query, string $value): \Illuminate\Database\Eloquent\Builder => $query
                            ->whereHas('item', fn (\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder => $query->where('item_group', $value)))),
                TernaryFilter::make('is_visible')
                    ->label('Tampil di Buyer')
                    ->trueLabel('Tampil')
                    ->falseLabel('Tidak Tampil'),
                TernaryFilter::make('is_featured')
                    ->label('Produk Unggulan')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
                TernaryFilter::make('show_price')
                    ->label('Tampilkan Harga')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
                TernaryFilter::make('show_stock')
                    ->label('Tampilkan Stok')
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
