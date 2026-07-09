<?php

namespace App\Filament\Admin\Resources\CustomerTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CustomerTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('default_price_list'),
                TextInput::make('default_warehouse'),
                TextInput::make('minimum_order_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('minimum_order_qty')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('allow_reward')
                    ->required(),
                Toggle::make('allow_promo')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
