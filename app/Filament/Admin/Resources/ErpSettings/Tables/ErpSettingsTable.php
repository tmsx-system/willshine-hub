<?php

namespace App\Filament\Admin\Resources\ErpSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ErpSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('erp_site_url')
                    ->searchable(),
                TextColumn::make('api_key')
                    ->searchable(),
                TextColumn::make('api_secret')
                    ->searchable(),
                TextColumn::make('default_company')
                    ->searchable(),
                TextColumn::make('default_selling_price_list')
                    ->searchable(),
                TextColumn::make('default_warehouse')
                    ->searchable(),
                TextColumn::make('default_so_naming_series')
                    ->searchable(),
                IconColumn::make('enable_auto_sync')
                    ->boolean(),
                TextColumn::make('last_sync_customer')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_sync_item')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_sync_stock')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_sync_price')
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
