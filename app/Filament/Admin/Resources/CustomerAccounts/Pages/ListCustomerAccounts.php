<?php

namespace App\Filament\Admin\Resources\CustomerAccounts\Pages;

use App\Filament\Admin\Resources\CustomerAccounts\CustomerAccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerAccounts extends ListRecords
{
    protected static string $resource = CustomerAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Data'),
        ];
    }
}
