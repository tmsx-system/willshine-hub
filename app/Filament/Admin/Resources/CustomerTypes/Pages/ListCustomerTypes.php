<?php

namespace App\Filament\Admin\Resources\CustomerTypes\Pages;

use App\Filament\Admin\Resources\CustomerTypes\CustomerTypeResource;
use App\Services\ERP\CustomerService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Throwable;

class ListCustomerTypes extends ListRecords
{
    protected static string $resource = CustomerTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncCustomerTypes')
                ->label('Sinkron Tipe Pelanggan')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->action(function (): void {
                    try {
                        app(CustomerService::class)->syncCustomerTypes();

                        Notification::make()
                            ->title('Tipe pelanggan berhasil disinkronkan')
                            ->success()
                            ->send();
                    } catch (Throwable $exception) {
                        report($exception);

                        Notification::make()
                            ->title('Sinkron tipe pelanggan gagal')
                            ->body($exception->getMessage())
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),
            CreateAction::make()->label('Tambah Data'),
        ];
    }
}
