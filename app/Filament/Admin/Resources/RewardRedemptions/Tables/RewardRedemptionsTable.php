<?php

namespace App\Filament\Admin\Resources\RewardRedemptions\Tables;

use App\Models\RewardRedemption;
use App\Services\RewardService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Throwable;

class RewardRedemptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('redemption_number')
                    ->label('Redemption')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customerAccount.customer_name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('customerAccount.salesPerson.name')
                    ->label('Sales Person')
                    ->searchable(),
                TextColumn::make('reward.name')
                    ->label('Reward')
                    ->searchable(),
                TextColumn::make('points_spent')
                    ->label('Points')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),
                TextColumn::make('processedBy.name')
                    ->label('Processed By')
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
                Action::make('approve')
                    ->label('Approve')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Approve this reward redemption and deduct customer points?')
                    ->visible(fn (RewardRedemption $record): bool => $record->status === 'pending')
                    ->action(function (RewardRedemption $record): void {
                        try {
                            app(RewardService::class)->approveRedemption($record, auth()->id());

                            Notification::make()
                                ->title('Reward redemption approved')
                                ->success()
                                ->send();
                        } catch (Throwable $exception) {
                            report($exception);

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
                    ->visible(fn (RewardRedemption $record): bool => $record->status === 'pending')
                    ->action(function (RewardRedemption $record, array $data): void {
                        $record->update([
                            'status' => 'rejected',
                            'rejection_note' => $data['rejection_note'] ?? null,
                            'processed_by' => auth()->id(),
                            'processed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Reward redemption rejected')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
