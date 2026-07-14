<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Widgets\Concerns\ScopesDashboardData;
use App\Models\PurchaseRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Schema;

class RevenueTrendChart extends ChartWidget
{
    use ScopesDashboardData;

    protected static ?int $sort = 20;

    protected ?string $heading = 'Omzet Disetujui 7 Hari Terakhir';

    protected ?string $description = 'Berdasarkan pengajuan buyer yang sudah disetujui sales.';

    protected string $color = 'success';

    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    protected function getData(): array
    {
        $labels = [];
        $values = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d M');

            if (!Schema::hasTable('purchase_requests')) {
                $values[] = 0;

                continue;
            }

            $values[] = (float) $this->applyCustomerAccountScope(
                PurchaseRequest::query()
                    ->where('status', 'approved')
                    ->whereDate('approved_at', $date->toDateString())
            )->sum('grand_total');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Omzet Approved',
                    'data' => $values,
                    'borderColor' => '#EC4899',
                    'backgroundColor' => 'rgba(236, 72, 153, 0.16)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
