<?php

namespace App\Filament\Admin\Resources\ErpItemPrices\Pages;

use App\Filament\Admin\Resources\ErpItemPrices\ErpItemPriceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditErpItemPrice extends EditRecord
{
    protected static string $resource = ErpItemPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->label('Hapus'),
        ];
    }
}
