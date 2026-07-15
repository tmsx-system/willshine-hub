<?php

namespace App\Filament\Admin\Resources\ErpSettings\Tables;

use App\Filament\Admin\Resources\Concerns\HasDateRangeFilters;
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
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Throwable;

class ErpSettingsTable
{
    use HasDateRangeFilters;

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('erp_site_url')
                    ->label('URL ERPNext')
                    ->searchable(),
                TextColumn::make('api_key')
                    ->label('API Key')
                    ->searchable(),
                TextColumn::make('api_secret')
                    ->label('API Secret')
                    ->searchable(),
                TextColumn::make('default_company')
                    ->label('Default Company')
                    ->searchable(),
                TextColumn::make('default_selling_price_list')
                    ->label('Price List Default')
                    ->searchable(),
                TextColumn::make('default_warehouse')
                    ->label('Default Gudang')
                    ->searchable(),
                TextColumn::make('default_so_naming_series')
                    ->label('Default Nomor SO')
                    ->searchable(),
                IconColumn::make('enable_auto_sync')
                    ->label('Auto Sinkron')
                    ->boolean(),
                TextColumn::make('last_sync_customer')
                    ->label('Sinkron Pelanggan')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_sync_item')
                    ->label('Sinkron Item')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_sync_stock')
                    ->label('Sinkron Stok')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('last_sync_price')
                    ->label('Sinkron Harga')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('default_company')
                    ->label('Default Company')
                    ->searchable()
                    ->options(fn (): array => ErpSetting::query()
                        ->whereNotNull('default_company')
                        ->distinct()
                        ->orderBy('default_company')
                        ->pluck('default_company', 'default_company')
                        ->all()),
                SelectFilter::make('default_selling_price_list')
                    ->label('Price List Default')
                    ->searchable()
                    ->options(fn (): array => ErpSetting::query()
                        ->whereNotNull('default_selling_price_list')
                        ->distinct()
                        ->orderBy('default_selling_price_list')
                        ->pluck('default_selling_price_list', 'default_selling_price_list')
                        ->all()),
                TernaryFilter::make('enable_auto_sync')
                    ->label('Auto Sinkron')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
                self::dateRangeFilter('last_sync_item', 'Tanggal Sinkron Item'),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                ActionGroup::make([
                    Action::make('testConnection')
                        ->label('Tes Koneksi')
                        ->icon('heroicon-o-signal')
                        ->color('gray')
                        ->action(function (): void {
                            try {
                                $user = app(FrappeClient::class)->testConnection();

                                Notification::make()
                                    ->title('ERP connection successful')
                                    ->body("Berhasil login sebagai {$user}.")
                                    ->success()
                                    ->send();
                            } catch (Throwable $exception) {
                                self::notifyFailure('Koneksi gagal', $exception);
                            }
                        }),
                    Action::make('syncItems')
                        ->label('Sinkron Item')
                        ->icon('heroicon-o-arrow-path')
                        ->color('primary')
                        ->url(fn(ErpSetting $record): string => route('admin.erp-settings.sync', [
                            'record' => $record->getKey(),
                            'type' => 'items',
                        ])),
                    Action::make('syncProductCategories')
                        ->label('Sinkron Grup Item')
                        ->icon('heroicon-o-tag')
                        ->color('primary')
                        ->url(fn(ErpSetting $record): string => route('admin.erp-settings.sync', [
                            'record' => $record->getKey(),
                            'type' => 'item-groups',
                        ])),
                    Action::make('syncCustomerTypes')
                        ->label('Sinkron Tipe Pelanggan')
                        ->icon('heroicon-o-identification')
                        ->color('primary')
                        ->url(fn(ErpSetting $record): string => route('admin.erp-settings.sync', [
                            'record' => $record->getKey(),
                            'type' => 'customer-types',
                        ])),
                    Action::make('syncCustomers')
                        ->label('Sinkron Pelanggan')
                        ->icon('heroicon-o-arrow-path')
                        ->color('primary')
                        ->url(fn(ErpSetting $record): string => route('admin.erp-settings.sync', [
                            'record' => $record->getKey(),
                            'type' => 'customers',
                        ])),
                    Action::make('syncPrices')
                        ->label('Sinkron Harga')
                        ->icon('heroicon-o-currency-dollar')
                        ->color('primary')
                        ->url(fn(ErpSetting $record): string => route('admin.erp-settings.sync', [
                            'record' => $record->getKey(),
                            'type' => 'prices',
                        ])),
                ]),
                EditAction::make()->label('Ubah'),
                DeleteAction::make()->label('Hapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Terpilih'),
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
