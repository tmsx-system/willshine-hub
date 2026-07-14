<?php

namespace App\Filament\Admin\Resources\ErpItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ErpItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('erp_item_id')
                    ->label('ID Item ERP')
                    ->searchable(),
                TextColumn::make('item_code')
                    ->label('Kode Item')
                    ->searchable(),
                TextColumn::make('item_name')
                    ->label('Nama Item')
                    ->searchable(),
                TextColumn::make('item_group')
                    ->label('Grup Item')
                    ->searchable(),
                TextColumn::make('stock_uom')
                    ->label('UOM Stok')
                    ->searchable(),
                TextColumn::make('brand')
                    ->label('Brand')
                    ->searchable()
                     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('company')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_stock_item')
                    ->label('Item Stok')
                    ->boolean(),
                IconColumn::make('disabled')
                    ->label('Nonaktif')
                    ->boolean(),
                IconColumn::make('has_batch_no')
                    ->label('Batch No')
                    ->boolean(),
                IconColumn::make('has_serial_no')
                    ->label('Serial No')
                    ->boolean()
                     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('erp_modified_at')
                    ->label('Diubah di ERP')
                    ->dateTime()
                    ->sortable()
                     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('last_synced_at')
                    ->label('Sinkron Terakhir')
                    ->dateTime()
                    ->sortable()
                     ->toggleable(isToggledHiddenByDefault: true),
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
