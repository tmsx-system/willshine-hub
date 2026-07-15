<?php

namespace App\Filament\Admin\Resources\Discounts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DiscountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Diskon')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Diskon')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('code')
                            ->label('Kode Diskon')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Kosongkan jika diskon otomatis tanpa kode voucher.'),
                        Select::make('discount_type')
                            ->label('Jenis Diskon')
                            ->required()
                            ->options([
                                'percentage' => 'Persentase',
                                'fixed' => 'Nominal Tetap',
                            ])
                            ->default('percentage'),
                        TextInput::make('discount_value')
                            ->label('Nilai Diskon')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('minimum_order_amount')
                            ->label('Minimum Order')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp'),
                        TextInput::make('maximum_discount_amount')
                            ->label('Maksimal Diskon')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->helperText('Dipakai terutama untuk diskon persentase.'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Target Diskon')
                    ->columns(2)
                    ->schema([
                        Select::make('scope')
                            ->label('Berlaku Untuk')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set): void {
                                $set('product_catalog_id', null);
                                $set('product_category_id', null);
                                $set('customer_type_id', null);
                                $set('customer_account_id', null);
                            })
                            ->options([
                                'order' => 'Semua Order',
                                'product' => 'Produk Tertentu',
                                'category' => 'Kategori Produk',
                                'customer_type' => 'Tipe Pelanggan',
                                'customer' => 'Akun Pelanggan',
                            ]),
                        Select::make('product_catalog_id')
                            ->label('Produk Katalog')
                            ->relationship('productCatalog', 'display_name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Get $get): bool => $get('scope') === 'product')
                            ->required(fn (Get $get): bool => $get('scope') === 'product'),
                        Select::make('product_category_id')
                            ->label('Kategori Produk')
                            ->relationship('productCategory', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Get $get): bool => $get('scope') === 'category')
                            ->required(fn (Get $get): bool => $get('scope') === 'category'),
                        Select::make('customer_type_id')
                            ->label('Tipe Pelanggan')
                            ->relationship('customerType', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Get $get): bool => $get('scope') === 'customer_type')
                            ->required(fn (Get $get): bool => $get('scope') === 'customer_type'),
                        Select::make('customer_account_id')
                            ->label('Akun Pelanggan')
                            ->relationship('customerAccount', 'customer_name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Get $get): bool => $get('scope') === 'customer')
                            ->required(fn (Get $get): bool => $get('scope') === 'customer'),
                    ])
                    ->columnSpanFull(),
                Section::make('Periode dan Batas Pemakaian')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('starts_at')
                            ->label('Mulai Berlaku'),
                        DateTimePicker::make('ends_at')
                            ->label('Berakhir Pada'),
                        TextInput::make('usage_limit')
                            ->label('Limit Total Penggunaan')
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('per_customer_limit')
                            ->label('Limit per Pelanggan')
                            ->numeric()
                            ->minValue(0),
                        Toggle::make('is_stackable')
                            ->label('Bisa Digabung dengan Diskon Lain')
                            ->default(false),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->required(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
