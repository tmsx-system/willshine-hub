<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Widgets\Concerns\ScopesDashboardData;
use App\Models\PurchaseRequest;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class LatestPurchaseRequests extends TableWidget
{
    use ScopesDashboardData;

    protected static ?int $sort = 40;

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    public static function canView(): bool
    {
        return Schema::hasTable('purchase_requests');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pengajuan Pesanan Terbaru')
            ->description('Order buyer terbaru yang masuk dari frontend.')
            ->query($this->query())
            ->columns([
                TextColumn::make('request_number')
                    ->label('No. Pengajuan')
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('customerAccount.salesPerson.name')
                    ->label('Sales'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
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
                TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR'),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->since(),
            ])
            ->paginated(false);
    }

    private function query(): Builder
    {
        return $this->applyCustomerAccountScope(
            PurchaseRequest::query()
                ->with(['customerAccount.salesPerson'])
                ->latest()
                ->limit(8)
        );
    }
}
