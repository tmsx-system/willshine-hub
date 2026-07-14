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
                    ->label('No. Pengajuan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customerAccount.customer_name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('customerAccount.salesPerson.name')
                    ->label('Sales')
                    ->searchable(),
                TextColumn::make('reward.name')
                    ->label('Reward')
                    ->searchable(),
                TextColumn::make('points_spent')
                    ->label('Poin')
                    ->numeric()
                    ->sortable(),
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
                TextColumn::make('processedBy.name')
                    ->label('Diproses Oleh')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Setujui')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Setujui penukaran reward ini dan kurangi poin pelanggan?')
                    ->visible(fn (RewardRedemption $record): bool => $record->status === 'pending')
                    ->action(function (RewardRedemption $record): void {
                        try {
                            app(RewardService::class)->approveRedemption($record, auth()->id());

                            Notification::make()
                                ->title('Penukaran reward disetujui')
                                ->success()
                                ->send();
                        } catch (Throwable $exception) {
                            report($exception);

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
                    ->visible(fn (RewardRedemption $record): bool => $record->status === 'pending')
                    ->action(function (RewardRedemption $record, array $data): void {
                        $record->update([
                            'status' => 'rejected',
                            'rejection_note' => $data['rejection_note'] ?? null,
                            'processed_by' => auth()->id(),
                            'processed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Penukaran reward ditolak')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
