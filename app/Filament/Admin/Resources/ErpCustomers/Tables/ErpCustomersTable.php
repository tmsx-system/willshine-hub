<?php

namespace App\Filament\Admin\Resources\ErpCustomers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ErpCustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('erp_customer_id')
                    ->searchable(),
                TextColumn::make('customer_code')
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->searchable(),
                TextColumn::make('customer_group')
                    ->searchable(),
                TextColumn::make('territory')
                    ->searchable(),
                TextColumn::make('customer_type_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('default_price_list')
                    ->searchable(),
                TextColumn::make('default_warehouse')
                    ->searchable(),
                TextColumn::make('credit_limit')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('disabled')
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
