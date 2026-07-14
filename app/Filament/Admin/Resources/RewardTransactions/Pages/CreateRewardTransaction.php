<?php

namespace App\Filament\Admin\Resources\RewardTransactions\Pages;

use App\Filament\Admin\Resources\RewardTransactions\RewardTransactionResource;
use App\Filament\Admin\Resources\RewardTransactions\Schemas\RewardTransactionForm;
use Filament\Resources\Pages\CreateRecord;

class CreateRewardTransaction extends CreateRecord
{
    protected static string $resource = RewardTransactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return RewardTransactionForm::mutateBeforeCreate($data);
    }
}
