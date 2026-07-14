<?php

namespace App\Filament\Admin\Resources\ProductCatalogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductCatalogsTable
{
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
                //
            ])
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
