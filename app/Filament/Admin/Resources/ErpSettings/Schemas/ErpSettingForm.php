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
                Section::make('ERP Connection')
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
                Section::make('Selling Defaults')
                    ->columns(2)
                    ->schema([
                        TextInput::make('default_company')
                            ->label('Default Company'),
                        TextInput::make('default_selling_price_list')
                            ->label('Default Selling Price List'),
                        TextInput::make('default_warehouse')
                            ->label('Default Warehouse'),
                        TextInput::make('default_so_naming_series')
                            ->label('Default Sales Order Naming Series'),
                    ]),
                Section::make('Sync Status')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('last_sync_customer')
                            ->label('Last Customer Sync')
                            ->disabled(),
                        DateTimePicker::make('last_sync_item')
                            ->label('Last Item Sync')
                            ->disabled(),
                        DateTimePicker::make('last_sync_stock')
                            ->label('Last Stock Sync')
                            ->disabled(),
                        DateTimePicker::make('last_sync_price')
                            ->label('Last Price Sync')
                            ->disabled(),
                        Toggle::make('enable_auto_sync')
                            ->label('Enable Auto Sync')
                            ->required(),
                    ])
                    ->columnSpan('full'),
            ]);
    }
}
