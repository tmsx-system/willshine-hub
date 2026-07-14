<?php

namespace App\Filament\Admin\Resources\ErpSettings\Pages;

use App\Filament\Admin\Resources\ErpSettings\ErpSettingResource;
use App\Services\ERP\FrappeClient;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Throwable;

class EditErpSetting extends EditRecord
{
    protected static string $resource = ErpSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('testConnection')
                ->label('Tes Koneksi')
                ->color('gray')
                ->action(function (): void {
                    try {
                        $user = app(FrappeClient::class)->testConnection();

                        Notification::make()
                            ->title('Koneksi ERP berhasil')
                            ->body("Berhasil login sebagai {$user}.")
                            ->success()
                            ->send();
                    } catch (Throwable $exception) {
                        $this->notifyFailure('Koneksi gagal', $exception);
                    }
                }),
            Action::make('syncItems')
                ->label('Sinkron Item')
                ->color('primary')
                ->url(fn (): string => route('admin.erp-settings.sync', [
                    'record' => $this->record->getKey(),
                    'type' => 'items',
                ])),
            Action::make('syncProductCategories')
                ->label('Sinkron Grup Item')
                ->icon('heroicon-o-tag')
                ->color('primary')
                ->url(fn (): string => route('admin.erp-settings.sync', [
                    'record' => $this->record->getKey(),
                    'type' => 'item-groups',
                ])),
            Action::make('syncCustomerTypes')
                ->label('Sinkron Tipe Pelanggan')
                ->icon('heroicon-o-identification')
                ->color('primary')
                ->url(fn (): string => route('admin.erp-settings.sync', [
                    'record' => $this->record->getKey(),
                    'type' => 'customer-types',
                ])),
            Action::make('syncCustomers')
                ->label('Sinkron Pelanggan')
                ->color('primary')
                ->url(fn (): string => route('admin.erp-settings.sync', [
                    'record' => $this->record->getKey(),
                    'type' => 'customers',
                ])),
            Action::make('syncPrices')
                ->label('Sinkron Harga')
                ->icon('heroicon-o-currency-dollar')
                ->color('primary')
                ->url(fn (): string => route('admin.erp-settings.sync', [
                    'record' => $this->record->getKey(),
                    'type' => 'prices',
                ])),
            DeleteAction::make()->label('Hapus'),
        ];
    }

    private function notifyFailure(string $title, Throwable $exception): void
    {
        report($exception);

        Notification::make()
            ->title($title)
            ->body($exception->getMessage())
            ->danger()
            ->persistent()
            ->send();
    }
}
