<?php

namespace App\Filament\Admin\Resources\Rewards\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RewardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Reward')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Reward')
                            ->required()
                            ->maxLength(255),
                        Select::make('category')
                            ->label('Kategori')
                            ->required()
                            ->options([
                                'Discount' => 'Diskon',
                                'Shipping' => 'Gratis/Ongkir',
                                'Gift' => 'Hadiah',
                                'Cashback' => 'Cashback',
                                'Service' => 'Layanan',
                            ])
                            ->default('Discount'),
                        TextInput::make('points_required')
                            ->label('Poin Dibutuhkan')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                        DatePicker::make('valid_until')
                            ->label('Berlaku Sampai'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Tampilan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('display_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->required(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
