<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Widgets\Concerns\ScopesDashboardData;
use App\Models\CustomerAccount;
use App\Models\ProductCatalog;
use App\Models\PurchaseRequest;
use App\Models\RewardRedemption;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;

class AdminStatsOverview extends StatsOverviewWidget
{
    use ScopesDashboardData;

    protected static ?int $sort = 10;

    protected ?string $heading = 'Ringkasan Operasional';

    protected ?string $description = 'Pantauan cepat untuk order buyer, omzet, pelanggan, produk, dan reward.';

    protected function getStats(): array
    {
        $todayRevenue = $this->approvedRevenue(now()->startOfDay(), now()->endOfDay());
        $monthRevenue = $this->approvedRevenue(now()->startOfMonth(), now()->endOfDay());

        return [
            Stat::make('Pengajuan Menunggu', $this->pendingPurchaseRequests())
                ->description('Order buyer yang perlu diproses sales')
                ->descriptionIcon(Heroicon::OutlinedClipboardDocumentCheck)
                ->color('warning')
                ->icon(Heroicon::OutlinedClock),
            Stat::make('Omzet Disetujui Hari Ini', $this->rupiah($todayRevenue))
                ->description('Total pengajuan yang sudah disetujui hari ini')
                ->descriptionIcon(Heroicon::OutlinedBanknotes)
                ->color('success')
                ->icon(Heroicon::OutlinedCurrencyDollar),
            Stat::make('Omzet Bulan Ini', $this->rupiah($monthRevenue))
                ->description('Akumulasi order approved bulan berjalan')
                ->descriptionIcon(Heroicon::OutlinedChartBar)
                ->color('success')
                ->icon(Heroicon::OutlinedPresentationChartLine),
            Stat::make('Akun Pelanggan Aktif', $this->activeCustomerAccounts())
                ->description('Customer account yang bisa login')
                ->descriptionIcon(Heroicon::OutlinedUserCircle)
                ->color('info')
                ->icon(Heroicon::OutlinedUserGroup),
            Stat::make('Produk Tampil di Buyer', $this->visibleProductCatalogs())
                ->description('Katalog aktif yang bisa dilihat buyer')
                ->descriptionIcon(Heroicon::OutlinedShoppingBag)
                ->color('primary')
                ->icon(Heroicon::OutlinedCube),
            Stat::make('Reward Menunggu', $this->pendingRewardRedemptions())
                ->description('Pengajuan tukar reward pending')
                ->descriptionIcon(Heroicon::OutlinedGift)
                ->color('warning')
                ->icon(Heroicon::OutlinedTicket),
        ];
    }

    private function pendingPurchaseRequests(): int
    {
        if (!Schema::hasTable('purchase_requests')) {
            return 0;
        }

        return $this->applyCustomerAccountScope(
            PurchaseRequest::query()->where('status', 'pending')
        )->count();
    }

    private function approvedRevenue($from, $to): float
    {
        if (!Schema::hasTable('purchase_requests')) {
            return 0;
        }

        return (float) $this->applyCustomerAccountScope(
            PurchaseRequest::query()
                ->where('status', 'approved')
                ->whereBetween('approved_at', [$from, $to])
        )->sum('grand_total');
    }

    private function activeCustomerAccounts(): int
    {
        if (!Schema::hasTable('customer_accounts')) {
            return 0;
        }

        $query = CustomerAccount::query()->where('is_active', true);
        $customerAccountIds = $this->assignedCustomerAccountIds();

        if ($customerAccountIds === null) {
            return $query->count();
        }

        if ($customerAccountIds === []) {
            return 0;
        }

        return $query->whereIn('id', $customerAccountIds)->count();
    }

    private function visibleProductCatalogs(): int
    {
        if (!Schema::hasTable('product_catalogs')) {
            return 0;
        }

        return ProductCatalog::query()
            ->where('is_visible', true)
            ->count();
    }

    private function pendingRewardRedemptions(): int
    {
        if (!Schema::hasTable('reward_redemptions')) {
            return 0;
        }

        return $this->applyCustomerAccountScope(
            RewardRedemption::query()->where('status', 'pending')
        )->count();
    }

    private function rupiah(float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
