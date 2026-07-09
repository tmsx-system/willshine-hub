<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                TextInput::make('customer_id')
                    ->numeric(),
                TextInput::make('customer_name'),
                TextInput::make('customer_type_id')
                    ->numeric(),
                TextInput::make('sales_person'),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('can_order')
                    ->required(),
                Toggle::make('can_view_price')
                    ->required(),
                Toggle::make('can_view_reward')
                    ->required(),
                DateTimePicker::make('last_login_at'),
            ]);
    }
}
