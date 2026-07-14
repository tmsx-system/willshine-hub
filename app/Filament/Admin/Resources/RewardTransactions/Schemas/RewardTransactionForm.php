<?php

namespace App\Filament\Admin\Resources\RewardTransactions\Schemas;

use App\Models\CustomerAccount;
use App\Services\RewardService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RewardTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Penyesuaian Poin Manual')
                    ->columns(2)
                    ->schema([
                        Select::make('customer_account_id')
                            ->label('Akun Pelanggan')
                            ->relationship('customerAccount', 'customer_name')
                            ->getOptionLabelFromRecordUsing(fn (CustomerAccount $record): string => "{$record->customer_name} ({$record->email})")
                            ->searchable(['customer_name', 'email'])
                            ->preload()
                            ->required(),
                        Select::make('type')
                            ->label('Tipe Transaksi')
                            ->required()
                            ->options([
                                'adjustment' => 'Penyesuaian',
                                'bonus' => 'Bonus',
                            ])
                            ->default('adjustment'),
                        TextInput::make('points')
                            ->label('Poin')
                            ->helperText('Gunakan nilai positif untuk tambah poin, negatif untuk koreksi pengurangan.')
                            ->required()
                            ->integer(),
                        TextInput::make('reference')
                            ->label('Referensi')
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Keterangan')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function mutateBeforeCreate(array $data): array
    {
        $balance = app(RewardService::class)->balance((int) $data['customer_account_id']);

        $data['status'] = 'approved';
        $data['balance_after'] = $balance + (int) $data['points'];

        return $data;
    }
}
