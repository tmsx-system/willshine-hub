<?php

namespace App\Filament\Admin\Resources\ErpItems\Pages;

use App\Filament\Admin\Resources\ErpItems\ErpItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListErpItems extends ListRecords
{
    protected static string $resource = ErpItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
