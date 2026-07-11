<?php

namespace App\Filament\Admin\Resources\ErpCustomers;

use App\Filament\Admin\Resources\ErpCustomers\Pages\CreateErpCustomer;
use App\Filament\Admin\Resources\ErpCustomers\Pages\EditErpCustomer;
use App\Filament\Admin\Resources\ErpCustomers\Pages\ListErpCustomers;
use App\Filament\Admin\Resources\ErpCustomers\Schemas\ErpCustomerForm;
use App\Filament\Admin\Resources\ErpCustomers\Tables\ErpCustomersTable;
use App\Models\ErpCustomer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ErpCustomerResource extends Resource
{
    protected static ?string $model = ErpCustomer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static \UnitEnum|string|null $navigationGroup = 'Customers';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'ERP Customers';

    protected static ?string $modelLabel = 'ERP Customer';

    protected static ?string $pluralModelLabel = 'ERP Customers';

    public static function form(Schema $schema): Schema
    {
        return ErpCustomerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ErpCustomersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListErpCustomers::route('/'),
            'create' => CreateErpCustomer::route('/create'),
            'edit' => EditErpCustomer::route('/{record}/edit'),
        ];
    }
}
