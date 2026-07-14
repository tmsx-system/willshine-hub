<?php

namespace App\Filament\Admin\Resources\ProductCatalogs\Pages;

use App\Filament\Admin\Resources\ProductCatalogs\ProductCatalogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductCatalog extends EditRecord
{
    protected static string $resource = ProductCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->label('Hapus'),
        ];
    }
}
