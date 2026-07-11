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
                    ->searchable(),
                TextColumn::make('item_code')
                    ->searchable(),
                TextColumn::make('item_name')
                    ->searchable(),
                TextColumn::make('item_group')
                    ->searchable(),
                TextColumn::make('stock_uom')
                    ->searchable(),
                TextColumn::make('brand')
                    ->searchable()
                     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('company')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_stock_item')
                    ->boolean(),
                IconColumn::make('disabled')
                    ->boolean(),
                IconColumn::make('has_batch_no')
                    ->boolean(),
                IconColumn::make('has_serial_no')
                    ->boolean()
                     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('erp_modified_at')
                    ->dateTime()
                    ->sortable()
                     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('last_synced_at')
                    ->dateTime()
                    ->sortable()
                     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
