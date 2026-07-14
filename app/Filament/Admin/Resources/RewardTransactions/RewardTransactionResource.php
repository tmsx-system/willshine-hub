<?php

namespace App\Filament\Admin\Resources\RewardTransactions;

use App\Filament\Admin\Resources\RewardTransactions\Pages\CreateRewardTransaction;
use App\Filament\Admin\Resources\RewardTransactions\Pages\ListRewardTransactions;
use App\Filament\Admin\Resources\RewardTransactions\Schemas\RewardTransactionForm;
use App\Filament\Admin\Resources\RewardTransactions\Tables\RewardTransactionsTable;
use App\Models\RewardTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RewardTransactionResource extends Resource
{
    protected static ?string $model = RewardTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static \UnitEnum|string|null $navigationGroup = 'Rewards';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Reward Point Ledger';

    protected static ?string $modelLabel = 'Reward Point Transaction';

    protected static ?string $pluralModelLabel = 'Reward Point Ledger';

    public static function form(Schema $schema): Schema
    {
        return RewardTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RewardTransactionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRewardTransactions::route('/'),
            'create' => CreateRewardTransaction::route('/create'),
        ];
    }
}
