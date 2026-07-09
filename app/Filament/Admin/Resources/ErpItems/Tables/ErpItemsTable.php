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
                    ->searchable(),
                ImageColumn::make('image_url'),
                ImageColumn::make('website_image_url'),
                IconColumn::make('is_stock_item')
                    ->boolean(),
                IconColumn::make('disabled')
                    ->boolean(),
                IconColumn::make('has_batch_no')
                    ->boolean(),
                IconColumn::make('has_serial_no')
                    ->boolean(),
                TextColumn::make('erp_modified_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_synced_at')
                    ->dateTime()
                    ->sortable(),
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
