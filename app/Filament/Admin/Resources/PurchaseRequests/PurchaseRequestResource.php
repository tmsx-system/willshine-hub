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

class PurchaseRequestResource extends Resource
{
    protected static ?string $model = PurchaseRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    // protected static \UnitEnum|string|null $navigationGroup = 'Customers';

    protected static ?int $navigationSort = 21;

    protected static ?string $navigationLabel = 'Buyer Order Requests';

    protected static ?string $modelLabel = 'Buyer Order Request';

    protected static ?string $pluralModelLabel = 'Buyer Order Requests';

    public static function table(Table $table): Table
    {
        return PurchaseRequestsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['customer', 'customerAccount.salesPerson']);
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
