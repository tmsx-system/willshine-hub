<?php

namespace App\Filament\Admin\Resources\CustomerAccounts\Tables;

use App\Filament\Admin\Resources\Concerns\HasDateRangeFilters;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CustomerAccountsTable
{
    use HasDateRangeFilters;

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
                SelectFilter::make('customer_type_id')
                    ->label('Tipe Pelanggan')
                    ->relationship('customerType', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('sales_user_id')
                    ->label('Sales')
                    ->relationship('salesPerson', 'name')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('is_active')
                    ->label('Akun Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
                TernaryFilter::make('can_order')
                    ->label('Boleh Order')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
                TernaryFilter::make('can_view_price')
                    ->label('Boleh Lihat Harga')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
                TernaryFilter::make('can_view_reward')
                    ->label('Boleh Lihat Reward')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
                self::dateRangeFilter('created_at', 'Tanggal Dibuat'),
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
