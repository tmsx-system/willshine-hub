<?php

namespace App\Filament\Admin\Resources\ErpCustomers\Tables;

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

class ErpCustomersTable
{
    use HasDateRangeFilters;

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('erp_customer_id')
                    ->label('ID Pelanggan ERP')
                    ->searchable(),
                TextColumn::make('customer_code')
                    ->label('Kode Pelanggan')
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->label('Nama Pelanggan')
                    ->searchable(),
                TextColumn::make('customer_group')
                    ->label('Grup')
                    ->searchable(),
                TextColumn::make('territory')
                    ->label('Area')
                    ->searchable(),
                TextColumn::make('customerType.name')
                    ->label('Tipe Pelanggan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('default_price_list')
                    ->label('Price List Default')
                    ->searchable(),
                TextColumn::make('default_warehouse')
                    ->label('Gudang')
                    ->searchable(),
                TextColumn::make('credit_limit')
                    ->label('Limit Kredit')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('disabled')
                    ->label('Nonaktif')
                    ->boolean(),
                TextColumn::make('erp_modified_at')
                    ->label('Diubah di ERP')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_synced_at')
                    ->label('Sinkron Terakhir')
                    ->dateTime()
                    ->sortable(),
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
                SelectFilter::make('customer_type_id')
                    ->label('Tipe Pelanggan')
                    ->relationship('customerType', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('customer_group')
                    ->label('Grup Pelanggan')
                    ->searchable()
                    ->options(fn (): array => \App\Models\ErpCustomer::query()
                        ->whereNotNull('customer_group')
                        ->distinct()
                        ->orderBy('customer_group')
                        ->pluck('customer_group', 'customer_group')
                        ->all()),
                SelectFilter::make('territory')
                    ->label('Area')
                    ->searchable()
                    ->options(fn (): array => \App\Models\ErpCustomer::query()
                        ->whereNotNull('territory')
                        ->distinct()
                        ->orderBy('territory')
                        ->pluck('territory', 'territory')
                        ->all()),
                SelectFilter::make('default_price_list')
                    ->label('Price List Default')
                    ->searchable()
                    ->options(fn (): array => \App\Models\ErpCustomer::query()
                        ->whereNotNull('default_price_list')
                        ->distinct()
                        ->orderBy('default_price_list')
                        ->pluck('default_price_list', 'default_price_list')
                        ->all()),
                TernaryFilter::make('disabled')
                    ->label('Status ERP')
                    ->trueLabel('Nonaktif')
                    ->falseLabel('Aktif'),
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
