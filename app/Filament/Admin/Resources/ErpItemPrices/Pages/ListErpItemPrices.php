<?php

namespace App\Filament\Admin\Resources\ErpItemPrices\Pages;

use App\Filament\Admin\Resources\ErpItemPrices\ErpItemPriceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListErpItemPrices extends ListRecords
{
    protected static string $resource = ErpItemPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Data'),
        ];
    }
}
