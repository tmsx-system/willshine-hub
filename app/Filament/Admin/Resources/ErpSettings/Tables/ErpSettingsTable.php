<?php

namespace App\Filament\Admin\Resources\ErpSettings\Tables;

use App\Services\ERP\FrappeClient;
use App\Models\ErpSetting;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ActionGroup;
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
                ActionGroup::make([
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
                        ->url(fn(ErpSetting $record): string => route('admin.erp-settings.sync', [
                            'record' => $record->getKey(),
                            'type' => 'items',
                        ])),
                    Action::make('syncProductCategories')
                        ->label('Sync Item Groups')
                        ->icon('heroicon-o-tag')
                        ->color('primary')
                        ->url(fn(ErpSetting $record): string => route('admin.erp-settings.sync', [
                            'record' => $record->getKey(),
                            'type' => 'item-groups',
                        ])),
                    Action::make('syncCustomerTypes')
                        ->label('Sync Customer Types')
                        ->icon('heroicon-o-identification')
                        ->color('primary')
                        ->url(fn(ErpSetting $record): string => route('admin.erp-settings.sync', [
                            'record' => $record->getKey(),
                            'type' => 'customer-types',
                        ])),
                    Action::make('syncCustomers')
                        ->label('Sync Customers')
                        ->icon('heroicon-o-arrow-path')
                        ->color('primary')
                        ->url(fn(ErpSetting $record): string => route('admin.erp-settings.sync', [
                            'record' => $record->getKey(),
                            'type' => 'customers',
                        ])),
                    Action::make('syncPrices')
                        ->label('Sync Prices')
                        ->icon('heroicon-o-currency-dollar')
                        ->color('primary')
                        ->url(fn(ErpSetting $record): string => route('admin.erp-settings.sync', [
                            'record' => $record->getKey(),
                            'type' => 'prices',
                        ])),
                ]),
                EditAction::make(),
                DeleteAction::make(),
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
