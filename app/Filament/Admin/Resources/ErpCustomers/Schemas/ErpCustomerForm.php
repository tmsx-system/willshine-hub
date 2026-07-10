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
                Section::make('Customer Identity')
                    ->columns(2)
                    ->schema([
                        TextInput::make('erp_customer_id')
                            ->label('ERP Customer ID')
                            ->required(),
                        TextInput::make('customer_code')
                            ->label('Customer Code'),
                        TextInput::make('customer_name')
                            ->label('Customer Name')
                            ->required(),
                        Select::make('customer_type_id')
                            ->label('Customer Type')
                            ->relationship('customerType', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
                Section::make('ERP Profile')
                    ->columns(2)
                    ->schema([
                        TextInput::make('customer_group')
                            ->label('Customer Group'),
                        TextInput::make('territory'),
                        TextInput::make('default_price_list')
                            ->label('Default Price List'),
                        TextInput::make('default_warehouse')
                            ->label('Default Warehouse'),
                        TextInput::make('credit_limit')
                            ->label('Credit Limit')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        Toggle::make('disabled')
                            ->label('Disabled')
                            ->required(),
                    ]),
                Section::make('Sync Metadata')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('erp_modified_at')
                            ->label('ERP Modified At'),
                        DateTimePicker::make('last_synced_at')
                            ->label('Last Synced At'),
                    ]),
            ]);
    }
}
