<?php

namespace App\Services\ERP;

use App\Models\CustomerProductCatalog;
use App\Models\PurchaseRequest;
use Exception;

class SalesOrderService
{
    public function __construct(private FrappeClient $client)
    {
    }

    public function createFromPurchaseRequest(PurchaseRequest $request): string
    {
        $this->assertDailyStockAvailable($request);

        $setting = $this->client->getSetting();
        $customer = $request->customer;
        $items = collect($request->items)
            ->map(function (array $item): array {
                return [
                    'item_code' => $item['item_code'] ?? null,
                    'qty' => (float) ($item['qty'] ?? 0),
                    'uom' => $item['uom'] ?? null,
                    'rate' => (float) ($item['price'] ?? 0),
                    'warehouse' => $item['warehouse'] ?? $this->client->getSetting()?->default_warehouse,
                ];
            })
            ->filter(fn (array $item): bool => !empty($item['item_code']) && $item['qty'] > 0)
            ->values()
            ->all();

        if ($items === []) {
            throw new Exception('Purchase request has no valid item lines.');
        }

        $payload = [
            'customer' => $customer->erp_customer_id,
            'transaction_date' => now()->toDateString(),
            'delivery_date' => now()->addDay()->toDateString(),
            'company' => $setting?->default_company,
            'selling_price_list' => $request->price_list ?: $customer->default_price_list ?: $setting?->default_selling_price_list,
            'set_warehouse' => $setting?->default_warehouse,
            'items' => $items,
        ];

        if ($setting?->default_so_naming_series) {
            $payload['naming_series'] = $setting->default_so_naming_series;
        }

        $salesOrder = $this->client->post('Sales Order', array_filter($payload, fn ($value) => $value !== null && $value !== ''));

        $salesOrderId = $salesOrder['name'] ?? null;

        if (!$salesOrderId) {
            throw new Exception('ERPNext created an invalid Sales Order response.');
        }

        return $salesOrderId;
    }

    private function assertDailyStockAvailable(PurchaseRequest $request): void
    {
        foreach ($request->items ?? [] as $item) {
            $itemCode = $item['item_code'] ?? null;
            $requestedQty = (float) ($item['qty'] ?? 0);

            if (!$itemCode || $requestedQty <= 0) {
                continue;
            }

            $rule = CustomerProductCatalog::query()
                ->where('customer_id', $request->customer_id)
                ->where('is_active', true)
                ->whereHas('productCatalog.item', fn ($query) => $query->where('item_code', $itemCode))
                ->first();

            if (!$rule) {
                throw new Exception("No active stock allocation was found for item {$itemCode}.");
            }

            $approvedQty = PurchaseRequest::query()
                ->where('customer_id', $request->customer_id)
                ->where('status', 'approved')
                ->whereDate('approved_at', now()->toDateString())
                ->where('id', '!=', $request->id)
                ->get()
                ->flatMap(fn (PurchaseRequest $approvedRequest) => $approvedRequest->items ?? [])
                ->filter(fn (array $approvedItem): bool => ($approvedItem['item_code'] ?? null) === $itemCode)
                ->sum(fn (array $approvedItem): float => (float) ($approvedItem['qty'] ?? 0));

            $remainingQty = max(0, (float) $rule->daily_quantity - (float) $approvedQty);

            if ($requestedQty > $remainingQty) {
                throw new Exception("Insufficient allocated stock for {$itemCode}. Remaining {$remainingQty}, requested {$requestedQty}.");
            }
        }
    }
}
