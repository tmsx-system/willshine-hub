<?php

namespace App\Filament\Admin\Resources\ErpItems\Tables;

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

class ErpItemsTable
{
    use HasDateRangeFilters;

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('erp_item_id')
                    ->label('ID Item')
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
                SelectFilter::make('item_group')
                    ->label('Grup Item')
                    ->searchable()
                    ->options(fn (): array => \App\Models\ErpItem::query()
                        ->whereNotNull('item_group')
                        ->distinct()
                        ->orderBy('item_group')
                        ->pluck('item_group', 'item_group')
                        ->all()),
                SelectFilter::make('company')
                    ->label('Company')
                    ->searchable()
                    ->options(fn (): array => \App\Models\ErpItem::query()
                        ->whereNotNull('company')
                        ->distinct()
                        ->orderBy('company')
                        ->pluck('company', 'company')
                        ->all()),
                SelectFilter::make('stock_uom')
                    ->label('UOM Stok')
                    ->searchable()
                    ->options(fn (): array => \App\Models\ErpItem::query()
                        ->whereNotNull('stock_uom')
                        ->distinct()
                        ->orderBy('stock_uom')
                        ->pluck('stock_uom', 'stock_uom')
                        ->all()),
                TernaryFilter::make('disabled')
                    ->label('Status Item')
                    ->trueLabel('Nonaktif')
                    ->falseLabel('Aktif'),
                TernaryFilter::make('is_stock_item')
                    ->label('Item Stok')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
                TernaryFilter::make('has_batch_no')
                    ->label('Pakai Batch No')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
                self::dateRangeFilter('last_synced_at', 'Tanggal Sinkron'),
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
