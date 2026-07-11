<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ErpSetting;
use App\Services\ERP\CustomerService;
use App\Services\ERP\ItemService;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Throwable;

class ErpSettingSyncController extends Controller
{
    public function __invoke(ErpSetting $record, string $type): RedirectResponse
    {
        try {
            match ($type) {
                'items' => app(ItemService::class)->syncItems(),
                'item-groups' => app(ItemService::class)->syncProductCategories(),
                'customer-types' => app(CustomerService::class)->syncCustomerTypes(),
                'customers' => app(CustomerService::class)->syncCustomers(),
                'prices' => app(ItemService::class)->syncPrices(),
            };

            Notification::make()
                ->title($this->label($type) . ' synchronization completed')
                ->success()
                ->send();
        } catch (Throwable $exception) {
            report($exception);

            Notification::make()
                ->title($this->label($type) . ' synchronization failed')
                ->body($exception->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }

        return redirect()->back();
    }

    private function label(string $type): string
    {
        return match ($type) {
            'items' => 'Item',
            'item-groups' => 'Item group',
            'customer-types' => 'Customer type',
            'customers' => 'Customer',
            'prices' => 'Price',
        };
    }
}
