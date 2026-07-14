<?php

namespace App\Filament\Admin\Resources\ErpCustomers\Pages;

use App\Filament\Admin\Resources\ErpCustomers\ErpCustomerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditErpCustomer extends EditRecord
{
    protected static string $resource = ErpCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->label('Hapus'),
        ];
    }
}
