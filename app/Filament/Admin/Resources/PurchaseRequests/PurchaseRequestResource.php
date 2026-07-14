<?php

namespace App\Filament\Admin\Resources\PurchaseRequests;

use App\Filament\Admin\Resources\PurchaseRequests\Pages\ListPurchaseRequests;
use App\Filament\Admin\Resources\PurchaseRequests\Tables\PurchaseRequestsTable;
use App\Models\CustomerAccount;
use App\Models\PurchaseRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class PurchaseRequestResource extends Resource
{
    protected static ?string $model = PurchaseRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    // protected static \UnitEnum|string|null $navigationGroup = 'Pesanan';

    protected static ?int $navigationSort = 21;

    protected static ?string $navigationLabel = 'Pengajuan Pesanan Buyer';

    protected static ?string $modelLabel = 'Pengajuan Pesanan Buyer';

    protected static ?string $pluralModelLabel = 'Pengajuan Pesanan Buyer';

    public static function table(Table $table): Table
    {
        return PurchaseRequestsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        if (!Schema::hasTable('purchase_requests')) {
            return null;
        }

        $count = static::applySalesVisibility(
            PurchaseRequest::query()->where('status', 'pending')
        )->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Pengajuan pesanan buyer yang masih menunggu approval';
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['customer', 'customerAccount.salesPerson']);

        return static::applySalesVisibility($query);
    }

    protected static function applySalesVisibility(Builder $query): Builder
    {
        $user = auth()->user();

        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        $hasSalesAssignments = CustomerAccount::query()
            ->whereNotNull('sales_user_id')
            ->exists();

        if (!$hasSalesAssignments) {
            return $query;
        }

        $matchedCustomerAccountIds = CustomerAccount::query()
            ->where('sales_user_id', $user->id)
            ->pluck('id');

        if ($matchedCustomerAccountIds->isEmpty()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereIn('customer_account_id', $matchedCustomerAccountIds);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchaseRequests::route('/'),
        ];
    }
}
