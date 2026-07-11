<?php

namespace App\Filament\Admin\Resources\CustomerAccounts\Pages;

use App\Filament\Admin\Resources\CustomerAccounts\CustomerAccountResource;
use App\Models\ErpCustomer;
use Filament\Resources\Pages\EditRecord;

class EditCustomerAccount extends EditRecord
{
    protected static string $resource = CustomerAccountResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['customer_id'])) {
            $customer = ErpCustomer::find($data['customer_id']);

            $data['customer_name'] = $customer?->customer_name;
            $data['customer_type_id'] = $customer?->customer_type_id;
        }

        return $data;
    }
}
