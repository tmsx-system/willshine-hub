<?php

namespace App\Filament\Admin\Resources\CustomerProductCatalogs\Pages;

use App\Filament\Admin\Resources\CustomerProductCatalogs\CustomerProductCatalogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomerProductCatalogs extends ListRecords
{
    protected static string $resource = CustomerProductCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Data'),
        ];
    }
}
