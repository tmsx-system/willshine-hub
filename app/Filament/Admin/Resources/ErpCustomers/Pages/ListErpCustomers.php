<?php

namespace App\Filament\Admin\Resources\ErpCustomers\Pages;

use App\Filament\Admin\Resources\ErpCustomers\ErpCustomerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListErpCustomers extends ListRecords
{
    protected static string $resource = ErpCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Data'),
        ];
    }
}
