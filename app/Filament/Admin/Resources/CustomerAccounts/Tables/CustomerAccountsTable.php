<?php

namespace App\Filament\Admin\Resources\CustomerAccounts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomerAccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->label('Email address')->searchable(),
                TextColumn::make('customer.customer_code')->label('Customer Code')->searchable()->sortable(),
                TextColumn::make('customer.customer_name')->label('Customer')->searchable(),
                TextColumn::make('customerType.name')->label('Customer Type')->searchable()->sortable(),
                TextColumn::make('salesPerson.name')->label('Sales Person')->searchable()->sortable(),
                IconColumn::make('is_active')->boolean(),
                IconColumn::make('can_order')->boolean(),
                IconColumn::make('can_view_price')->boolean(),
                IconColumn::make('can_view_reward')->boolean(),
                TextColumn::make('last_login_at')->dateTime()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
