<?php

namespace App\Filament\Admin\Resources\ErpSettings\Pages;

use App\Filament\Admin\Resources\ErpSettings\ErpSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListErpSettings extends ListRecords
{
    protected static string $resource = ErpSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Data'),
        ];
    }
}
