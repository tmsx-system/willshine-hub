<?php

namespace App\Filament\Admin\Resources\ProductCategories\Pages;

use App\Filament\Admin\Resources\ProductCategories\ProductCategoryResource;
use App\Services\ERP\ItemService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Throwable;

class ListProductCategories extends ListRecords
{
    protected static string $resource = ProductCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncProductCategories')
                ->label('Sinkron Grup Item')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->requiresConfirmation()
                ->modalDescription('Ambil data Item Group dari ERPNext dan buat/update kategori produk?')
                ->action(function (): void {
                    try {
                        app(ItemService::class)->syncProductCategories();

                        Notification::make()
                            ->title('Kategori produk berhasil disinkronkan')
                            ->success()
                            ->send();
                    } catch (Throwable $exception) {
                        report($exception);

                        Notification::make()
                            ->title('Sinkron kategori produk gagal')
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
