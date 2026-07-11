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
                ->label('Test Connection')
                ->color('gray')
                ->action(function (): void {
                    try {
                        $user = app(FrappeClient::class)->testConnection();

                        Notification::make()
                            ->title('ERP connection successful')
                            ->body("Authenticated as {$user}.")
                            ->success()
                            ->send();
                    } catch (Throwable $exception) {
                        $this->notifyFailure('Connection failed', $exception);
                    }
                }),
            Action::make('syncItems')
                ->label('Sync Items')
                ->color('primary')
                ->url(fn (): string => route('admin.erp-settings.sync', [
                    'record' => $this->record->getKey(),
                    'type' => 'items',
                ])),
            Action::make('syncProductCategories')
                ->label('Sync Item Groups')
                ->icon('heroicon-o-tag')
                ->color('primary')
                ->url(fn (): string => route('admin.erp-settings.sync', [
                    'record' => $this->record->getKey(),
                    'type' => 'item-groups',
                ])),
            Action::make('syncCustomerTypes')
                ->label('Sync Customer Types')
                ->icon('heroicon-o-identification')
                ->color('primary')
                ->url(fn (): string => route('admin.erp-settings.sync', [
                    'record' => $this->record->getKey(),
                    'type' => 'customer-types',
                ])),
            Action::make('syncCustomers')
                ->label('Sync Customers')
                ->color('primary')
                ->url(fn (): string => route('admin.erp-settings.sync', [
                    'record' => $this->record->getKey(),
                    'type' => 'customers',
                ])),
            Action::make('syncPrices')
                ->label('Sync Prices')
                ->icon('heroicon-o-currency-dollar')
                ->color('primary')
                ->url(fn (): string => route('admin.erp-settings.sync', [
                    'record' => $this->record->getKey(),
                    'type' => 'prices',
                ])),
            DeleteAction::make(),
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
