<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ErpSettingSyncController;
use App\Http\Controllers\Buyer\BuyerOrderController;
use App\Http\Controllers\Buyer\BuyerRewardController;
use App\Models\CustomerProductCatalog;
use App\Models\ErpItemPrice;
use App\Models\ErpSetting;
use App\Models\ErpItem;
use App\Models\ProductCategory;
use App\Models\PurchaseRequest;
use App\Models\Reward;
use App\Services\RewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

$publicImageUrl = function (?string $path): ?string {
    if (!$path) {
        return null;
    }

    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }

    $path = ltrim($path, '/');

    if (str_starts_with($path, 'images/') || str_starts_with($path, 'storage/')) {
        return '/' . $path;
    }

    if (str_starts_with($path, 'files/')) {
        $baseUrl = rtrim((string) ErpSetting::query()->value('erp_site_url'), '/');

        return $baseUrl ? $baseUrl . '/' . $path : '/' . $path;
    }

    return Storage::disk('public')->url($path);
};

$defaultSellingPriceList = fn (): string => ErpSetting::query()->value('default_selling_price_list') ?: 'Standard Selling';

$erpItemPrice = function (string $itemCode, ?string $uom, ?string $priceList): ?float {
    if (!$priceList) {
        return null;
    }

    if (!Schema::hasTable('erp_item_prices')) {
        return null;
    }

    $today = now()->toDateString();

    $query = ErpItemPrice::query()
        ->where('item_code', $itemCode)
        ->where('price_list', $priceList)
        ->where(function ($query) use ($today) {
            $query->whereNull('valid_from')->orWhere('valid_from', '<=', $today);
        })
        ->where(function ($query) use ($today) {
            $query->whereNull('valid_upto')->orWhere('valid_upto', '>=', $today);
        });

    if ($uom) {
        $query->orderByRaw('CASE WHEN uom = ? THEN 0 WHEN uom IS NULL THEN 1 ELSE 2 END', [$uom]);
    }

    $price = $query
        ->orderByDesc('valid_from')
        ->first();

    return $price ? (float) $price->price_list_rate : null;
};

$erpItemPriceWithFallback = function (
    string $itemCode,
    ?string $uom,
    ?string $customerPriceList,
    ?string $defaultPriceList
) use ($erpItemPrice): array {
    $customerPrice = $customerPriceList
        ? $erpItemPrice($itemCode, $uom, $customerPriceList)
        : null;

    if ($customerPrice !== null) {
        return [
            'price' => $customerPrice,
            'price_list' => $customerPriceList,
            'source' => 'customer',
        ];
    }

    $defaultPrice = $defaultPriceList
        ? $erpItemPrice($itemCode, $uom, $defaultPriceList)
        : null;

    return [
        'price' => $defaultPrice,
        'price_list' => $defaultPrice !== null ? $defaultPriceList : $customerPriceList,
        'source' => $defaultPrice !== null ? 'default' : 'missing',
    ];
};

$publicProducts = function (int $limit = 48) use ($publicImageUrl, $erpItemPrice, $defaultSellingPriceList) {
    $publicPriceList = $defaultSellingPriceList();

    return ErpItem::query()
        ->with('catalog.category')
        ->where('disabled', false)
        ->whereHas('catalog', fn ($query) => $query->where('is_visible', true))
        ->limit($limit)
        ->get()
        ->map(function (ErpItem $item) use ($publicImageUrl, $erpItemPrice, $publicPriceList) {
            $price = $erpItemPrice($item->item_code, $item->stock_uom, $publicPriceList);

            return [
                'id' => $item->id,
                'name' => $item->catalog->display_name ?: $item->item_name,
                'category' => $item->catalog->category?->name ?: $item->item_group,
                'grade' => $item->catalog->display_description ?: $item->brand,
                'packaging' => $item->catalog->display_description ?: $item->stock_uom,
                'uom' => $item->stock_uom,
                'price' => $price !== null ? 'Rp ' . number_format($price, 0, ',', '.') : null,
                'can_view_price' => $price !== null,
                'price_list' => $publicPriceList,
                'price_note' => $price !== null ? 'Harga publik/default. Login untuk harga customer perusahaan Anda.' : "Harga default belum diisi untuk {$publicPriceList}",
                'stock_status' => 'Tersedia',
                'image' => $publicImageUrl($item->catalog->display_image_url ?: $item->image_url),
                'badge' => $item->catalog->is_featured ? 'Featured' : null,
            ];
        });
};

$publicCategories = function () use ($publicImageUrl) {
    return ProductCategory::query()
        ->with(['catalogs' => fn ($query) => $query->where('is_visible', true)->orderByDesc('is_featured')])
        ->withCount(['catalogs' => fn ($query) => $query->where('is_visible', true)])
        ->where('is_active', true)
        ->orderBy('display_order')
        ->orderBy('name')
        ->get()
        ->map(fn (ProductCategory $category) => [
            'id' => $category->id,
            'name' => $category->name,
            'count' => $category->catalogs_count . ' products',
            'image' => $publicImageUrl($category->image_url ?: $category->catalogs->first()?->display_image_url),
        ]);
};

Route::get('/', fn () => Inertia::render('Landing', [
    'products' => $publicProducts(4),
    'categories' => $publicCategories(),
]))->name('landing');

Route::get('/products', function () use ($publicProducts) {
    return Inertia::render('PublicCatalog', ['products' => $publicProducts()]);
})->name('public.products');

Route::get('/categories', function () use ($publicProducts) {
    return Inertia::render('PublicCatalog', ['products' => $publicProducts()]);
})->name('public.categories');

Route::get('/promotions', fn () => Inertia::render('Landing', [
    'products' => $publicProducts(4),
    'categories' => $publicCategories(),
]))
    ->name('public.promotions');

Route::get('/rewards', function () {
    $rewards = Schema::hasTable('rewards')
        ? Reward::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('valid_until')->orWhere('valid_until', '>=', now()->toDateString());
            })
            ->orderBy('display_order')
            ->orderBy('points_required')
            ->get()
            ->map(fn (Reward $reward): array => [
                'id' => $reward->id,
                'name' => $reward->name,
                'points' => $reward->points_required . ' pts',
                'desc' => $reward->description,
            ])
        : [];

    return Inertia::render('PublicRewards', ['rewards' => $rewards]);
})->name('public.rewards');

Route::redirect('/public-rewards', '/rewards');

Route::get('/about', fn () => Inertia::render('Landing', [
    'products' => $publicProducts(4),
    'categories' => $publicCategories(),
]))
    ->name('public.about');

Route::get('/contact', fn () => Inertia::render('Landing', [
    'products' => $publicProducts(4),
    'categories' => $publicCategories(),
]))
    ->name('public.contact');

Route::get('/login', fn () => Inertia::render('Auth/Login'))->name('login');
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin/erp-settings/{record}/sync/{type}', ErpSettingSyncController::class)
    ->middleware('auth')
    ->where('type', 'items|item-groups|customer-types|customers|prices')
    ->name('admin.erp-settings.sync');

Route::prefix('buyer')->name('buyer.')->middleware('auth:customer')->group(function () use ($publicImageUrl, $erpItemPriceWithFallback, $defaultSellingPriceList) {
    Route::get('/', function (Request $request) {
        $user = $request->user('customer');
        $hasPurchaseRequestsTable = Schema::hasTable('purchase_requests');
        $recentOrders = $hasPurchaseRequestsTable
            ? PurchaseRequest::query()
                ->where('customer_account_id', $user->id)
                ->latest()
                ->limit(5)
                ->get()
            : collect();

        return Inertia::render('Buyer/Dashboard', [
            'buyer' => [
                'name' => $user->customer_name ?: $user->name,
                'contact' => $user->name,
                'email' => $user->email,
                'tier' => null,
                'avatar' => strtoupper(substr($user->name, 0, 2)),
            ],
            'stats' => [
                'stock_allocation' => 0,
                'pending_orders' => $hasPurchaseRequestsTable
                    ? PurchaseRequest::query()
                        ->where('customer_account_id', $user->id)
                        ->where('status', 'pending')
                        ->count()
                    : 0,
                'total_spend' => 0,
                'reward_points' => Schema::hasTable('reward_transactions')
                    ? app(RewardService::class)->balance($user)
                    : 0,
            ],
            'recent_orders' => $recentOrders->map(fn (PurchaseRequest $order): array => [
                'id' => $order->request_number,
                'items' => count($order->items ?? []),
                'date' => $order->created_at?->toDateString(),
                'total' => (float) $order->grand_total,
                'status' => match ($order->status) {
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    default => 'Pending',
                },
            ]),
        ]);
    })->name('dashboard');

    Route::get('/catalog', function (Request $request) use ($publicImageUrl, $erpItemPriceWithFallback, $defaultSellingPriceList) {
        $account = $request->user('customer')->loadMissing('customer.customerType', 'customerType');
        $customerId = $account->customer_id;
        $customerPriceList = $account->customer?->default_price_list
            ?: $account->customer?->customerType?->default_price_list
            ?: $account->customerType?->default_price_list;
        $defaultPriceList = $defaultSellingPriceList();
        $canViewPrice = (bool) $account->can_view_price;
        $customerRules = collect();
        $approvedQuantities = collect();

        if ($customerId) {
            $customerRules = CustomerProductCatalog::query()
                ->with('productCatalog.item', 'productCatalog.category')
                ->where('customer_id', $customerId)
                ->where('is_active', true)
                ->whereHas('productCatalog', fn ($query) => $query->where('is_visible', true))
                ->get();

            if (Schema::hasTable('purchase_requests')) {
                $approvedQuantities = PurchaseRequest::query()
                    ->where('customer_id', $customerId)
                    ->where('status', 'approved')
                    ->whereDate('approved_at', now()->toDateString())
                    ->get()
                    ->flatMap(fn (PurchaseRequest $request) => $request->items ?? [])
                    ->filter(fn (array $item): bool => !empty($item['item_code']))
                    ->groupBy('item_code')
                    ->map(fn ($items): float => (float) $items->sum(fn (array $item): float => (float) ($item['qty'] ?? 0)));
            }
        }

        if ($customerRules->isNotEmpty()) {
            $products = $customerRules
                ->map(function (CustomerProductCatalog $rule) use ($publicImageUrl, $erpItemPriceWithFallback, $customerPriceList, $defaultPriceList, $canViewPrice, $approvedQuantities) {
                    $catalog = $rule->productCatalog;
                    $item = $catalog->item;
                    $dailyQuantity = (float) $rule->daily_quantity;
                    $approvedQuantity = (float) $approvedQuantities->get($item?->item_code, 0);
                    $remainingQuantity = max(0, $dailyQuantity - $approvedQuantity);
                    $configuredMaximum = (float) ($rule->maximum_qty ?: $catalog->maximum_qty ?: $remainingQuantity);
                    $maximumQuantity = $remainingQuantity > 0
                        ? min($configuredMaximum > 0 ? $configuredMaximum : $remainingQuantity, $remainingQuantity)
                        : 0;

                    $priceData = $item && $canViewPrice
                        ? $erpItemPriceWithFallback($item->item_code, $item->stock_uom, $customerPriceList, $defaultPriceList)
                        : ['price' => null, 'price_list' => $customerPriceList ?: $defaultPriceList, 'source' => 'hidden'];
                    $priceNote = match ($priceData['source']) {
                        'customer' => "Harga customer: {$priceData['price_list']}",
                        'default' => "Harga default item: {$priceData['price_list']}",
                        'hidden' => 'Harga tidak ditampilkan untuk akun ini',
                        default => 'Harga belum tersedia',
                    };

                    return [
                        'id' => $catalog->id,
                        'item_code' => $item?->item_code,
                        'name' => $catalog->display_name ?: $catalog->item_name,
                        'category' => $catalog->category?->name ?: $item?->item_group ?: 'Produk',
                        'grade' => $catalog->display_description ?: $item?->brand ?: 'Fresh',
                        'uom' => $item?->stock_uom ?: 'Unit',
                        'price' => $priceData['price'],
                        'price_list' => $priceData['price_list'],
                        'price_note' => $priceNote,
                        'price_source' => $priceData['source'],
                        'can_view_price' => $canViewPrice,
                        'stock' => $remainingQuantity,
                        'stock_status' => $remainingQuantity > 0 ? 'full' : 'empty',
                        'image' => $publicImageUrl($catalog->display_image_url ?: $item?->image_url),
                        'daily_quantity' => $remainingQuantity,
                        'minimum_qty' => (float) ($rule->minimum_qty ?: $catalog->minimum_qty),
                        'maximum_qty' => $maximumQuantity,
                        'allocation_note' => $rule->note,
                    ];
                })
                ->values();

            return Inertia::render('Buyer/Catalog', [
                'categories' => $products
                    ->pluck('category')
                    ->filter()->unique()->prepend('Semua')->values(),
                'products' => $products,
                'uses_customer_rules' => true,
            ]);
        }

        $items = ErpItem::query()
            ->with('catalog.category')
            ->where('disabled', false)
            ->whereHas('catalog', fn ($query) => $query->where('is_visible', true))
            ->limit(100)
            ->get();

        return Inertia::render('Buyer/Catalog', [
            'categories' => $items
                ->map(fn ($item) => $item->catalog->category?->name ?: $item->item_group)
                ->filter()->unique()->prepend('Semua')->values(),
            'products' => $items->map(function ($item) use ($publicImageUrl, $erpItemPriceWithFallback, $customerPriceList, $defaultPriceList, $canViewPrice) {
                $priceData = $canViewPrice
                    ? $erpItemPriceWithFallback($item->item_code, $item->stock_uom, $customerPriceList, $defaultPriceList)
                    : ['price' => null, 'price_list' => $customerPriceList ?: $defaultPriceList, 'source' => 'hidden'];
                $priceNote = match ($priceData['source']) {
                    'customer' => "Harga customer: {$priceData['price_list']}",
                    'default' => "Harga default item: {$priceData['price_list']}",
                    'hidden' => 'Harga tidak ditampilkan untuk akun ini',
                    default => 'Harga belum tersedia',
                };

                return [
                    'id' => $item->id,
                    'item_code' => $item->item_code,
                    'name' => $item->catalog->display_name ?: $item->item_name,
                    'category' => $item->catalog->category?->name ?: $item->item_group ?: 'Produk',
                    'grade' => $item->catalog->display_description ?: $item->brand ?: 'Fresh',
                    'uom' => $item->stock_uom ?: 'Unit',
                    'price' => $priceData['price'],
                    'price_list' => $priceData['price_list'],
                    'price_note' => $priceNote,
                    'price_source' => $priceData['source'],
                    'can_view_price' => $canViewPrice,
                    'stock' => 0,
                    'stock_status' => 'empty',
                    'image' => $publicImageUrl($item->catalog->display_image_url ?: $item->image_url),
                    'daily_quantity' => null,
                    'minimum_qty' => (float) $item->catalog->minimum_qty,
                    'maximum_qty' => $item->catalog->maximum_qty ? (float) $item->catalog->maximum_qty : null,
                    'allocation_note' => null,
                ];
            }),
            'uses_customer_rules' => false,
        ]);
    })->name('catalog');

    Route::get('/cart', fn () => Inertia::render('Buyer/Cart', ['items' => []]))->name('cart');
    Route::post('/cart/submit', [BuyerOrderController::class, 'store'])->name('cart.submit');
    Route::get('/orders', [BuyerOrderController::class, 'index'])->name('orders');

    Route::get('/rewards', [BuyerRewardController::class, 'index'])->name('rewards');
    Route::post('/rewards/redeem', [BuyerRewardController::class, 'redeem'])->name('rewards.redeem');

    Route::get('/settings', function (Request $request) {
        $user = $request->user('customer');

        return Inertia::render('Buyer/Settings', [
            'buyer' => [
                'name' => $user->customer_name ?: $user->name,
                'contact' => $user->name,
                'email' => $user->email,
                'phone' => null,
                'address' => null,
                'npwp' => null,
                'tier' => null,
            ],
        ]);
    })->name('settings');

    Route::post('/settings', function (Request $request) {
        $user = $request->user('customer');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('customer_accounts', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return back()->with('status', 'Pengaturan akun berhasil diperbarui.');
    })->name('settings.update');
});
