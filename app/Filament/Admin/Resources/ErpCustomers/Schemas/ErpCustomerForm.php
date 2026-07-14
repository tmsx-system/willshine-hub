<?php

namespace App\Filament\Admin\Resources\ErpCustomers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ErpCustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Pelanggan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('erp_customer_id')
                            ->label('ERP Customer ID')
                            ->required(),
                        TextInput::make('customer_code')
                            ->label('Kode Pelanggan'),
                        TextInput::make('customer_name')
                            ->label('Nama Pelanggan')
                            ->required(),
                        Select::make('customer_type_id')
                            ->label('Tipe Pelanggan')
                            ->relationship('customerType', 'name')
                            ->searchable()
                            ->preload(),
                    ])
                      ->columnSpan('full'),
                Section::make('Profil ERP')
                    ->columns(2)
                    ->schema([
                        TextInput::make('customer_group')
                            ->label('Grup Pelanggan'),
                        TextInput::make('territory')
                            ->label('Area/Territory'),
                        TextInput::make('default_price_list')
                            ->label('Price List Default'),
                        TextInput::make('default_warehouse')
                            ->label('Default Gudang'),
                        TextInput::make('credit_limit')
                            ->label('Limit Kredit')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        Toggle::make('disabled')
                            ->label('Nonaktif di ERP')
                            ->required(),
                    ]),
                Section::make('Info Sinkronisasi')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('erp_modified_at')
                            ->label('Diubah di ERP Pada'),
                        DateTimePicker::make('last_synced_at')
                            ->label('Sinkron Terakhir'),
                    ]),
            ]);
    }
}
