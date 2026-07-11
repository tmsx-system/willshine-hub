<?php

namespace App\Filament\Admin\Resources\CustomerProductCatalogs\Pages;

use App\Filament\Admin\Resources\CustomerProductCatalogs\CustomerProductCatalogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerProductCatalog extends CreateRecord
{
    protected static string $resource = CustomerProductCatalogResource::class;
}
