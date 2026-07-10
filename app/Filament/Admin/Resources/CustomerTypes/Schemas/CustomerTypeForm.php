<?php

namespace App\Filament\Admin\Resources\CustomerTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Type')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('code')
                            ->required(),
                        Textarea::make('description')
                            ->columnSpanFull(),
                    ]),
                Section::make('Order Defaults')
                    ->columns(2)
                    ->schema([
                        TextInput::make('default_price_list')
                            ->label('Default Price List'),
                        TextInput::make('default_warehouse')
                            ->label('Default Warehouse'),
                        TextInput::make('minimum_order_amount')
                            ->label('Minimum Order Amount')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('minimum_order_qty')
                            ->label('Minimum Order Qty')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),
                Section::make('Rules')
                    ->columns(3)
                    ->schema([
                        Toggle::make('allow_reward')
                            ->label('Allow Reward')
                            ->required(),
                        Toggle::make('allow_promo')
                            ->label('Allow Promo')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->required(),
                    ]),
            ]);
    }
}
