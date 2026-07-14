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
                    ->label('Nama')
                    ->required(),
                TextInput::make('email')
                    ->label('Email Login')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at')
                    ->label('Email Terverifikasi Pada'),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->helperText('Kosongkan saat edit jika tidak ingin mengganti password.'),
                Toggle::make('is_active')
                    ->label('Akun Aktif')
                    ->required(),
                DateTimePicker::make('last_login_at')
                    ->label('Login Terakhir'),
            ]);
    }
}
