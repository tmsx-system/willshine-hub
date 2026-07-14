<?php

namespace App\Filament\Admin\Resources\CustomerAccounts;

use App\Filament\Admin\Resources\Concerns\HasResourceNavigationBadge;
use App\Filament\Admin\Resources\CustomerAccounts\Pages\CreateCustomerAccount;
use App\Filament\Admin\Resources\CustomerAccounts\Pages\EditCustomerAccount;
use App\Filament\Admin\Resources\CustomerAccounts\Pages\ListCustomerAccounts;
use App\Filament\Admin\Resources\CustomerAccounts\Schemas\CustomerAccountForm;
use App\Filament\Admin\Resources\CustomerAccounts\Tables\CustomerAccountsTable;
use App\Models\CustomerAccount;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerAccountResource extends Resource
{
    use HasResourceNavigationBadge;

    protected static ?string $model = CustomerAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static \UnitEnum|string|null $navigationGroup = 'Pelanggan';

    protected static ?int $navigationSort = 24;

    protected static ?string $navigationLabel = 'Akun Login Pelanggan';

    protected static ?string $modelLabel = 'Akun Login Pelanggan';

    protected static ?string $pluralModelLabel = 'Akun Login Pelanggan';

    public static function form(Schema $schema): Schema
    {
        return CustomerAccountForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerAccountsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomerAccounts::route('/'),
            'create' => CreateCustomerAccount::route('/create'),
            'edit' => EditCustomerAccount::route('/{record}/edit'),
        ];
    }
}
