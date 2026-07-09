<?php

namespace App\Filament\Admin\Resources\ErpCustomers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ErpCustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('erp_customer_id')
                    ->required(),
                TextInput::make('customer_code'),
                TextInput::make('customer_name')
                    ->required(),
                TextInput::make('customer_group'),
                TextInput::make('territory'),
                TextInput::make('customer_type_id')
                    ->numeric(),
                TextInput::make('default_price_list'),
                TextInput::make('default_warehouse'),
                TextInput::make('credit_limit')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Toggle::make('disabled')
                    ->required(),
                DateTimePicker::make('erp_modified_at'),
                DateTimePicker::make('last_synced_at'),
            ]);
    }
}
