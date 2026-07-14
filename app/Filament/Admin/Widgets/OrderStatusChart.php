<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Widgets\Concerns\ScopesDashboardData;
use App\Models\PurchaseRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Schema;

class OrderStatusChart extends ChartWidget
{
    use ScopesDashboardData;

    protected static ?int $sort = 30;

    protected ?string $heading = 'Status Pengajuan Pesanan';

    protected ?string $description = 'Komposisi pengajuan buyer berdasarkan status.';

    protected string $color = 'primary';

    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $pending = $this->countStatus('pending');
        $approved = $this->countStatus('approved');
        $rejected = $this->countStatus('rejected');

        return [
            'datasets' => [
                [
                    'data' => [$pending, $approved, $rejected],
                    'backgroundColor' => ['#F59E0B', '#10B981', '#EF4444'],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => ['Menunggu', 'Disetujui', 'Ditolak'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    private function countStatus(string $status): int
    {
        if (!Schema::hasTable('purchase_requests')) {
            return 0;
        }

        return $this->applyCustomerAccountScope(
            PurchaseRequest::query()->where('status', $status)
        )->count();
    }
}
