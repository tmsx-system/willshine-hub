<?php

namespace App\Filament\Admin\Resources\CustomerAccounts\Schemas;

use App\Models\ErpCustomer;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class CustomerAccountForm
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
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->helperText('Kosongkan saat edit jika tidak ingin mengganti password.'),
                Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'customer_name')
                    ->getOptionLabelFromRecordUsing(fn (ErpCustomer $record): string => "{$record->customer_code} - {$record->customer_name}")
                    ->searchable(['customer_code', 'customer_name'])
                    ->preload()
                    ->live()
                    ->required()
                    ->afterStateUpdated(function (Set $set, ?int $state): void {
                        $customer = $state ? ErpCustomer::with('customerType')->find($state) : null;

                        $set('customer_name', $customer?->customer_name);
                        $set('customer_type_id', $customer?->customer_type_id);
                        $set('customer_type_name', $customer?->customerType?->name);
                    }),
                TextInput::make('customer_name')
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('customer_type_name')
                    ->label('Customer Type')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($state, $record) => $record?->customerType?->name),
                TextInput::make('customer_type_id')
                    ->hidden()
                    ->dehydrated(),
                DateTimePicker::make('last_login_at'),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
                Toggle::make('can_order')
                    ->required()
                    ->default(true),
                Toggle::make('can_view_price')
                    ->required()
                    ->default(true),
                Toggle::make('can_view_reward')
                    ->required()
                    ->default(true),
            ]);
    }
}
