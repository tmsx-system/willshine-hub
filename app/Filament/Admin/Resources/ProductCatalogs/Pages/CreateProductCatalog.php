<?php

namespace App\Filament\Admin\Resources\ProductCatalogs\Pages;

use App\Filament\Admin\Resources\ProductCatalogs\ProductCatalogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductCatalog extends CreateRecord
{
    protected static string $resource = ProductCatalogResource::class;
}
