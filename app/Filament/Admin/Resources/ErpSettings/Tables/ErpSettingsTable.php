<?php

namespace App\Filament\Admin\Resources\ErpSettings\Tables;

use App\Services\ERP\CustomerService;
use App\Services\ERP\FrappeClient;
use App\Services\ERP\ItemService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Throwable;

class ErpSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('erp_site_url')
                    ->searchable(),
                TextColumn::make('api_key')
                    ->searchable(),
                TextColumn::make('api_secret')
                    ->searchable(),
                TextColumn::make('default_company')
                    ->searchable(),
                TextColumn::make('default_selling_price_list')
                    ->searchable(),
                TextColumn::make('default_warehouse')
                    ->searchable(),
                TextColumn::make('default_so_naming_series')
                    ->searchable(),
                IconColumn::make('enable_auto_sync')
                    ->boolean(),
                TextColumn::make('last_sync_customer')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_sync_item')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_sync_stock')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_sync_price')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('testConnection')
                    ->label('Test')
                    ->icon('heroicon-o-signal')
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
                            self::notifyFailure('Connection failed', $exception);
                        }
                    }),
                Action::make('syncItems')
                    ->label('Sync Items')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalDescription('Fetch all permitted Item records from ERPNext now? Synced items will also create hidden catalog drafts when missing.')
                    ->action(function (): void {
                        try {
                            app(ItemService::class)->syncItems();

                            Notification::make()
                                ->title('Item synchronization completed')
                                ->success()
                                ->send();
                        } catch (Throwable $exception) {
                            self::notifyFailure('Item synchronization failed', $exception);
                        }
                    }),
                Action::make('syncCustomers')
                    ->label('Sync Customers')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalDescription('Fetch all permitted Customer records from ERPNext now?')
                    ->action(function (): void {
                        try {
                            app(CustomerService::class)->syncCustomers();

                            Notification::make()
                                ->title('Customer synchronization completed')
                                ->success()
                                ->send();
                        } catch (Throwable $exception) {
                            self::notifyFailure('Customer synchronization failed', $exception);
                        }
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function notifyFailure(string $title, Throwable $exception): void
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
