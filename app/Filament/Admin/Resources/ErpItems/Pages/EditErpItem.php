<?php

namespace App\Filament\Admin\Resources\ErpItems\Pages;

use App\Filament\Admin\Resources\ErpItems\ErpItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditErpItem extends EditRecord
{
    protected static string $resource = ErpItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->label('Hapus'),
        ];
    }
}
