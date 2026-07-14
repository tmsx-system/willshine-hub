<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\ErpItem;
use App\Models\ErpSetting;
use App\Models\ProductCatalog;
use App\Models\PurchaseRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BuyerOrderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if (!Schema::hasTable('purchase_requests')) {
            return back()->withErrors(['items' => 'Tabel purchase_requests belum ada. Jalankan php artisan migrate terlebih dahulu.']);
        }

        $account = $request->user('customer')->loadMissing('customer.customerType', 'customerType');

        abort_unless($account->can_order, 403);

        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required'],
            'items.*.item_code' => ['nullable', 'string'],
            'items.*.name' => ['required', 'string'],
            'items.*.uom' => ['nullable', 'string'],
            'items.*.qty' => ['required', 'numeric', 'min:1'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['nullable', 'string', 'max:60'],
        ]);

        $priceList = $account->customer?->default_price_list
            ?: $account->customer?->customerType?->default_price_list
            ?: $account->customerType?->default_price_list
            ?: ErpSetting::query()->value('default_selling_price_list');

        $items = collect($data['items'])->map(function (array $item): array {
            $itemCode = $item['item_code'] ?? null;
            $catalog = null;

            if (!$itemCode && !empty($item['id'])) {
                $catalog = ProductCatalog::query()->with('item')->find($item['id']);
                $itemCode = $catalog?->item?->item_code;
            }

            $erpItem = $itemCode
                ? ErpItem::query()->where('item_code', $itemCode)->first()
                : ErpItem::query()->find($item['id']);
            $itemCode = $itemCode ?: $erpItem?->item_code;
            $qty = (float) $item['qty'];
            $price = (float) ($item['price'] ?? 0);

            return [
                'id' => $item['id'],
                'item_code' => $itemCode,
                'name' => $item['name'],
                'uom' => $item['uom'] ?? $erpItem?->stock_uom,
                'qty' => $qty,
                'price' => $price,
                'line_total' => $qty * $price,
            ];
        })
            ->filter(fn (array $item): bool => !empty($item['item_code']))
            ->values();

        if ($items->isEmpty()) {
            return back()->withErrors(['items' => 'Tidak ada item valid untuk dibuatkan pesanan. Refresh katalog lalu coba lagi.']);
        }

        $subtotal = $items->sum('line_total');
        $taxTotal = round($subtotal * 0.11);

        PurchaseRequest::create([
            'request_number' => 'PR-' . now()->format('YmdHis') . '-' . $account->id . '-' . strtoupper(Str::random(4)),
            'customer_account_id' => $account->id,
            'customer_id' => $account->customer_id,
            'customer_name' => $account->customer_name ?: $account->customer?->customer_name ?: $account->name,
            'price_list' => $priceList,
            'payment_method' => $data['payment_method'] ?? null,
            'status' => 'pending',
            'items' => $items->all(),
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'grand_total' => $subtotal + $taxTotal,
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('buyer.orders')->with('status', 'Pesanan berhasil dikirim dan menunggu approval sales.');
    }

    public function index(Request $request): Response
    {
        $account = $request->user('customer');

        if (!Schema::hasTable('purchase_requests')) {
            return Inertia::render('Buyer/Orders', ['orders' => []]);
        }

        $orders = PurchaseRequest::query()
            ->where('customer_account_id', $account->id)
            ->latest()
            ->get()
            ->map(fn (PurchaseRequest $order): array => $this->serializeOrder($order));

        return Inertia::render('Buyer/Orders', ['orders' => $orders]);
    }

    private function serializeOrder(PurchaseRequest $order): array
    {
        return [
            'id' => $order->request_number,
            'status' => match ($order->status) {
                'approved' => 'Approved',
                'rejected' => 'Rejected',
                default => 'Pending',
            },
            'date' => $order->created_at?->toDateString(),
            'items' => count($order->items ?? []),
            'payment' => $order->payment_method ?: '-',
            'total' => (float) $order->grand_total,
            'erp_sales_order_id' => $order->erp_sales_order_id,
            'items_detail' => collect($order->items ?? [])->map(fn (array $item): array => [
                'id' => $item['item_code'] ?? $item['id'] ?? null,
                'name' => $item['name'] ?? $item['item_code'] ?? 'Item',
                'qty' => (float) ($item['qty'] ?? 0),
                'uom' => $item['uom'] ?? null,
                'price' => (float) ($item['price'] ?? 0),
            ])->all(),
        ];
    }
}
