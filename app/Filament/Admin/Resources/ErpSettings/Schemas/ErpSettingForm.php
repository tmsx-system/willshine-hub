<?php

namespace App\Filament\Admin\Resources\ErpSettings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ErpSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('erp_site_url'),
                TextInput::make('api_key'),
                TextInput::make('api_secret'),
                TextInput::make('default_company'),
                TextInput::make('default_selling_price_list'),
                TextInput::make('default_warehouse'),
                TextInput::make('default_so_naming_series'),
                Toggle::make('enable_auto_sync')
                    ->required(),
                DateTimePicker::make('last_sync_customer'),
                DateTimePicker::make('last_sync_item'),
                DateTimePicker::make('last_sync_stock'),
                DateTimePicker::make('last_sync_price'),
            ]);
    }
}
