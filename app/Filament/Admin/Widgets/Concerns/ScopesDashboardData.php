<?php

namespace App\Filament\Admin\Widgets\Concerns;

use App\Models\CustomerAccount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait ScopesDashboardData
{
    protected function assignedCustomerAccountIds(): ?array
    {
        if (!Schema::hasTable('customer_accounts')) {
            return [];
        }

        $user = auth()->user();

        if (!$user) {
            return [];
        }

        $hasSalesAssignments = CustomerAccount::query()
            ->whereNotNull('sales_user_id')
            ->exists();

        if (!$hasSalesAssignments) {
            return null;
        }

        return CustomerAccount::query()
            ->where('sales_user_id', $user->id)
            ->pluck('id')
            ->all();
    }

    protected function applyCustomerAccountScope(Builder $query): Builder
    {
        $customerAccountIds = $this->assignedCustomerAccountIds();

        if ($customerAccountIds === null) {
            return $query;
        }

        if ($customerAccountIds === []) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereIn('customer_account_id', $customerAccountIds);
    }
}
