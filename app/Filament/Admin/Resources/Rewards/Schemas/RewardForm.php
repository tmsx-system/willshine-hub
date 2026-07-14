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
                Section::make('Reward')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('category')
                            ->required()
                            ->options([
                                'Discount' => 'Discount',
                                'Shipping' => 'Shipping',
                                'Gift' => 'Gift',
                                'Cashback' => 'Cashback',
                                'Service' => 'Service',
                            ])
                            ->default('Discount'),
                        TextInput::make('points_required')
                            ->label('Points Required')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                        DatePicker::make('valid_until')
                            ->label('Valid Until'),
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Display')
                    ->columns(2)
                    ->schema([
                        TextInput::make('display_order')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->required(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
