<?php

namespace App\Filament\Admin\Resources\PurchaseRequests\Tables;

use App\Filament\Admin\Resources\Concerns\HasDateRangeFilters;
use App\Models\PurchaseRequest;
use App\Models\User;
use App\Services\ERP\SalesOrderService;
use App\Services\RewardService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

class PurchaseRequestsTable
{
    use HasDateRangeFilters;

    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('request_number')
                    ->label('No. Pengajuan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.customer_code')
                    ->label('Kode Pelanggan')
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('customerAccount.salesPerson.name')
                    ->label('Sales')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => 'Menunggu',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),
                TextColumn::make('items')
                    ->label('Jumlah Item')
                    ->state(fn (PurchaseRequest $record): int => count($record->items ?? [])),
                TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('price_list')
                    ->label('Daftar Harga')
                    ->toggleable(),
                TextColumn::make('erp_sales_order_id')
                    ->label('Sales Order ERP')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
                SelectFilter::make('customer_account_id')
                    ->label('Akun Pelanggan')
                    ->relationship('customerAccount', 'customer_name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('sales_user_id')
                    ->label('Sales')
                    ->searchable()
                    ->options(fn (): array => User::query()
                        ->whereHas('assignedCustomerAccounts')
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['value'] ?? null, fn (Builder $query, string $value): Builder => $query
                            ->whereHas('customerAccount', fn (Builder $query): Builder => $query->where('sales_user_id', $value)))),
                self::dateRangeFilter('created_at', 'Tanggal Pengajuan'),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                Action::make('viewItems')
                    ->label('Lihat Item')
                    ->icon(Heroicon::OutlinedListBullet)
                    ->color('gray')
                    ->modalHeading(fn (PurchaseRequest $record): string => $record->request_number)
                    ->modalWidth('4xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->schema([
                        Section::make('Ringkasan Pengajuan')
                            ->columns(3)
                            ->schema([
                                TextEntry::make('customer_name')
                                    ->label('Pelanggan'),
                                TextEntry::make('customerAccount.salesPerson.name')
                                    ->label('Sales')
                                    ->placeholder('-'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->label('Status')
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        default => 'Menunggu',
                                    })
                                    ->color(fn (string $state): string => match ($state) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        default => 'warning',
                                    }),
                                TextEntry::make('price_list')
                                    ->label('Daftar Harga')
                                    ->placeholder('-'),
                                TextEntry::make('payment_method')
                                    ->label('Pembayaran')
                                    ->placeholder('-'),
                                TextEntry::make('grand_total')
                                    ->label('Total Akhir')
                                    ->money('IDR'),
                                TextEntry::make('notes')
                                    ->label('Catatan')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                                TextEntry::make('erp_error')
                                    ->label('Error ERP')
                                    ->placeholder('-')
                                    ->color('danger')
                                    ->columnSpanFull(),
                            ]),
                        Section::make('Item yang Diajukan')
                            ->schema([
                                RepeatableEntry::make('items')
                                    ->hiddenLabel()
                                    ->columns(6)
                                    ->schema([
                                        TextEntry::make('item_code')
                                            ->label('Kode Item')
                                            ->weight('bold'),
                                        TextEntry::make('name')
                                            ->label('Produk')
                                            ->columnSpan(2),
                                        TextEntry::make('qty')
                                            ->label('Qty')
                                            ->numeric(decimalPlaces: 2),
                                        TextEntry::make('uom')
                                            ->label('UOM')
                                            ->placeholder('-'),
                                        TextEntry::make('price')
                                            ->label('Harga')
                                            ->money('IDR'),
                                        TextEntry::make('line_total')
                                            ->label('Subtotal')
                                            ->money('IDR')
                                            ->weight('bold'),
                                    ]),
                            ]),
                    ]),
                Action::make('approve')
                    ->label('Setujui')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Setujui pengajuan buyer ini dan buat Sales Order di ERPNext?')
                    ->visible(fn (PurchaseRequest $record): bool => $record->status === 'pending')
                    ->action(function (PurchaseRequest $record): void {
                        try {
                            $record->loadMissing('customer');
                            $salesOrderId = app(SalesOrderService::class)->createFromPurchaseRequest($record);

                            $record->update([
                                'status' => 'approved',
                                'approved_by' => auth()->id(),
                                'approved_at' => now(),
                                'erp_sales_order_id' => $salesOrderId,
                                'erp_error' => null,
                            ]);

                            app(RewardService::class)->earnFromPurchaseRequest($record->refresh());

                            Notification::make()
                                ->title('Pengajuan disetujui')
                                ->body("Sales Order {$salesOrderId} berhasil dibuat di ERPNext.")
                                ->success()
                                ->send();
                        } catch (Throwable $exception) {
                            report($exception);

                            $record->update(['erp_error' => $exception->getMessage()]);

                            Notification::make()
                                ->title('Approval gagal')
                                ->body($exception->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->schema([
                        Textarea::make('rejection_note')
                            ->label('Alasan penolakan')
                            ->rows(3),
                    ])
                    ->visible(fn (PurchaseRequest $record): bool => $record->status === 'pending')
                    ->action(function (PurchaseRequest $record, array $data): void {
                        $record->update([
                            'status' => 'rejected',
                            'rejected_by' => auth()->id(),
                            'rejected_at' => now(),
                            'rejection_note' => $data['rejection_note'] ?? null,
                        ]);

                        Notification::make()
                            ->title('Pengajuan ditolak')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
