<?php

namespace App\Filament\Admin\Resources\ErpSettings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ErpSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Koneksi ERP')
                    ->columns(2)
                    ->schema([
                        TextInput::make('erp_site_url')
                            ->label('ERPNext Site URL')
                            ->url()
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('api_key')
                            ->label('ERP API Key')
                            ->password()
                            ->revealable()
                            ->required(),
                        TextInput::make('api_secret')
                            ->label('ERP API Secret')
                            ->password()
                            ->revealable()
                            ->required(),
                    ]),
                Section::make('Pengaturan Default Penjualan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('default_company')
                            ->label('Default Company'),
                        TextInput::make('default_selling_price_list')
                            ->label('Price List Penjualan Default'),
                        TextInput::make('default_warehouse')
                            ->label('Default Gudang'),
                        TextInput::make('default_so_naming_series')
                            ->label('Default Nomor Sales Order'),
                    ]),
                Section::make('Status Sinkronisasi')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('last_sync_customer')
                            ->label('Sinkron Pelanggan Terakhir')
                            ->disabled(),
                        DateTimePicker::make('last_sync_item')
                            ->label('Sinkron Item Terakhir')
                            ->disabled(),
                        DateTimePicker::make('last_sync_stock')
                            ->label('Sinkron Stok Terakhir')
                            ->disabled(),
                        DateTimePicker::make('last_sync_price')
                            ->label('Sinkron Harga Terakhir')
                            ->disabled(),
                        Toggle::make('enable_auto_sync')
                            ->label('Aktifkan Auto Sinkron')
                            ->required(),
                    ])
                    ->columnSpan('full'),
            ]);
    }
}
