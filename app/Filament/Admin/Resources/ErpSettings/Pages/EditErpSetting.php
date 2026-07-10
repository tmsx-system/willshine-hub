<?php

namespace App\Filament\Admin\Resources\ErpSettings\Pages;

use App\Filament\Admin\Resources\ErpSettings\ErpSettingResource;
use App\Services\ERP\CustomerService;
use App\Services\ERP\FrappeClient;
use App\Services\ERP\ItemService;
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
                ->requiresConfirmation()
                ->modalDescription('Fetch all permitted Item records from ERPNext now?')
                ->action(function (): void {
                    try {
                        app(ItemService::class)->syncItems();
                        $this->refreshFormData(['last_sync_item']);

                        Notification::make()
                            ->title('Item synchronization completed')
                            ->success()
                            ->send();
                    } catch (Throwable $exception) {
                        $this->notifyFailure('Item synchronization failed', $exception);
                    }
                }),
            Action::make('syncCustomers')
                ->label('Sync Customers')
                ->color('primary')
                ->requiresConfirmation()
                ->modalDescription('Fetch all permitted Customer records from ERPNext now?')
                ->action(function (): void {
                    try {
                        app(CustomerService::class)->syncCustomers();
                        $this->refreshFormData(['last_sync_customer']);

                        Notification::make()
                            ->title('Customer synchronization completed')
                            ->success()
                            ->send();
                    } catch (Throwable $exception) {
                        $this->notifyFailure('Customer synchronization failed', $exception);
                    }
                }),
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
