<?php

namespace App\Filament\Admin\Resources\ErpSettings\Pages;

use App\Filament\Admin\Resources\ErpSettings\ErpSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditErpSetting extends EditRecord
{
    protected static string $resource = ErpSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
