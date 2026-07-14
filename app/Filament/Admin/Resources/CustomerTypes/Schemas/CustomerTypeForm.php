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
                Section::make('Tipe Pelanggan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Tipe')
                            ->required(),
                        TextInput::make('code')
                            ->label('Kode')
                            ->required(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ]),
                Section::make('Pengaturan Default Order')
                    ->columns(2)
                    ->schema([
                        TextInput::make('default_price_list')
                            ->label('Price List Default'),
                        TextInput::make('default_warehouse')
                            ->label('Default Gudang'),
                        TextInput::make('minimum_order_amount')
                            ->label('Minimal Nilai Order')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('minimum_order_qty')
                            ->label('Minimal Qty Order')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),
                Section::make('Aturan')
                    ->columns(3)
                    ->schema([
                        Toggle::make('allow_reward')
                            ->label('Boleh Reward')
                            ->required(),
                        Toggle::make('allow_promo')
                            ->label('Boleh Promo')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ])
                    ->columnSpan('full'),
            ]);
    }
}
