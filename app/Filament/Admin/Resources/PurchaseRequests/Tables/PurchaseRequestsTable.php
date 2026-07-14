<?php

namespace App\Filament\Admin\Resources\PurchaseRequests\Tables;

use App\Models\PurchaseRequest;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Throwable;

class PurchaseRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('request_number')
                    ->label('Request')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer.customer_code')
                    ->label('Customer Code')
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('customerAccount.salesPerson.name')
                    ->label('Sales Person')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),
                TextColumn::make('items')
                    ->label('Items')
                    ->state(fn (PurchaseRequest $record): int => count($record->items ?? [])),
                TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('price_list')
                    ->label('Price List')
                    ->toggleable(),
                TextColumn::make('erp_sales_order_id')
                    ->label('ERP Sales Order')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Requested At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->recordActions([
                Action::make('viewItems')
                    ->label('Items')
                    ->icon(Heroicon::OutlinedListBullet)
                    ->color('gray')
                    ->modalHeading(fn (PurchaseRequest $record): string => $record->request_number)
                    ->modalWidth('4xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->schema([
                        Section::make('Request Summary')
                            ->columns(3)
                            ->schema([
                                TextEntry::make('customer_name')
                                    ->label('Customer'),
                                TextEntry::make('customerAccount.salesPerson.name')
                                    ->label('Sales Person')
                                    ->placeholder('-'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                                    ->color(fn (string $state): string => match ($state) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        default => 'warning',
                                    }),
                                TextEntry::make('price_list')
                                    ->label('Price List')
                                    ->placeholder('-'),
                                TextEntry::make('payment_method')
                                    ->label('Payment')
                                    ->placeholder('-'),
                                TextEntry::make('grand_total')
                                    ->label('Grand Total')
                                    ->money('IDR'),
                                TextEntry::make('notes')
                                    ->label('Notes')
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                                TextEntry::make('erp_error')
                                    ->label('ERP Error')
                                    ->placeholder('-')
                                    ->color('danger')
                                    ->columnSpanFull(),
                            ]),
                        Section::make('Requested Items')
                            ->schema([
                                RepeatableEntry::make('items')
                                    ->hiddenLabel()
                                    ->columns(6)
                                    ->schema([
                                        TextEntry::make('item_code')
                                            ->label('Item Code')
                                            ->weight('bold'),
                                        TextEntry::make('name')
                                            ->label('Product')
                                            ->columnSpan(2),
                                        TextEntry::make('qty')
                                            ->label('Qty')
                                            ->numeric(decimalPlaces: 2),
                                        TextEntry::make('uom')
                                            ->label('UOM')
                                            ->placeholder('-'),
                                        TextEntry::make('price')
                                            ->label('Rate')
                                            ->money('IDR'),
                                        TextEntry::make('line_total')
                                            ->label('Line Total')
                                            ->money('IDR')
                                            ->weight('bold'),
                                    ]),
                            ]),
                    ]),
                Action::make('approve')
                    ->label('Approve')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Approve this buyer request and create a Sales Order in ERPNext?')
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
                                ->title('Request approved')
                                ->body("Sales Order {$salesOrderId} created in ERPNext.")
                                ->success()
                                ->send();
                        } catch (Throwable $exception) {
                            report($exception);

                            $record->update(['erp_error' => $exception->getMessage()]);

                            Notification::make()
                                ->title('Approval failed')
                                ->body($exception->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->schema([
                        Textarea::make('rejection_note')
                            ->label('Reject reason')
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
                            ->title('Request rejected')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
