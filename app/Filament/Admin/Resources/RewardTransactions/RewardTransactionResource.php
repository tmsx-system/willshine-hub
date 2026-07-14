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

    protected static \UnitEnum|string|null $navigationGroup = 'Reward';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Riwayat Poin Reward';

    protected static ?string $modelLabel = 'Transaksi Poin Reward';

    protected static ?string $pluralModelLabel = 'Riwayat Poin Reward';

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
