<?php

namespace App\Filament\Admin\Resources\CustomerProductCatalogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerProductCatalogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.customer_code')
                    ->label('Customer Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('productCatalog.item_code')
                    ->label('Item Code')
                    ->searchable(),
                TextColumn::make('productCatalog.display_name')
                    ->label('Product')
                    ->searchable(),
                TextColumn::make('daily_quantity')
                    ->label('Daily Qty')
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
                IconColumn::make('is_active')
                    ->boolean(),
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
