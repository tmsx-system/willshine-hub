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
                TextColumn::make('name')->label('Nama Kontak')->searchable(),
                TextColumn::make('email')->label('Email Login')->searchable(),
                TextColumn::make('customer.customer_code')->label('Kode Pelanggan')->searchable()->sortable(),
                TextColumn::make('customer.customer_name')->label('Pelanggan')->searchable(),
                TextColumn::make('customerType.name')->label('Tipe Pelanggan')->searchable()->sortable(),
                TextColumn::make('salesPerson.name')->label('Sales')->searchable()->sortable(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                IconColumn::make('can_order')->label('Boleh Order')->boolean(),
                IconColumn::make('can_view_price')->label('Lihat Harga')->boolean(),
                IconColumn::make('can_view_reward')->label('Lihat Reward')->boolean(),
                TextColumn::make('last_login_at')->label('Login Terakhir')->dateTime()->sortable(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Diubah')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
