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
                    ->label('Catalog Code')
                    ->searchable(),
                TextColumn::make('item_name')
                    ->label('Catalog Item Name')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('display_name')
                    ->searchable(),
                ImageColumn::make('display_image_url'),
                IconColumn::make('is_visible')
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->boolean(),
                TextColumn::make('display_order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('minimum_qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('maximum_qty')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('allow_decimal_qty')
                    ->boolean(),
                IconColumn::make('show_stock')
                    ->boolean(),
                IconColumn::make('show_price')
                    ->boolean(),
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
