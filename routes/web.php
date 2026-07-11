<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ErpSettingSyncController;
use App\Models\CustomerProductCatalog;
use App\Models\ErpItemPrice;
use App\Models\ErpSetting;
use App\Models\ErpItem;
use App\Models\ProductCategory;
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

$publicProducts = function (int $limit = 48) use ($publicImageUrl) {
    return ErpItem::query()
        ->with('catalog.category')
        ->where('disabled', false)
        ->whereHas('catalog', fn ($query) => $query->where('is_visible', true))
        ->limit($limit)
        ->get()
        ->map(fn (ErpItem $item) => [
            'id' => $item->id,
            'name' => $item->catalog->display_name ?: $item->item_name,
            'category' => $item->catalog->category?->name ?: $item->item_group,
            'grade' => $item->catalog->display_description ?: $item->brand,
            'packaging' => $item->catalog->display_description ?: $item->stock_uom,
            'uom' => $item->stock_uom,
            'price' => $item->catalog->show_price ? 'Masuk untuk melihat harga' : 'Hubungi kami',
            'stock_status' => 'Tersedia',
            'image' => $publicImageUrl($item->catalog->display_image_url ?: $item->image_url),
            'badge' => $item->catalog->is_featured ? 'Featured' : null,
        ]);
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

Route::get('/rewards', fn () => Inertia::render('PublicRewards', ['rewards' => []]))
    ->name('public.rewards');

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

Route::prefix('buyer')->name('buyer.')->middleware('auth:customer')->group(function () use ($publicImageUrl, $erpItemPrice) {
    Route::get('/', function (Request $request) {
        $user = $request->user('customer');

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
                'pending_orders' => 0,
                'total_spend' => 0,
                'reward_points' => 0,
            ],
            'recent_orders' => [],
        ]);
    })->name('dashboard');

    Route::get('/catalog', function (Request $request) use ($publicImageUrl, $erpItemPrice) {
        $account = $request->user('customer')->loadMissing('customer.customerType', 'customerType');
        $customerId = $account->customer_id;
        $priceList = $account->customer?->default_price_list
            ?: $account->customer?->customerType?->default_price_list
            ?: $account->customerType?->default_price_list
            ?: ErpSetting::query()->value('default_selling_price_list');
        $canViewPrice = (bool) $account->can_view_price;
        $customerRules = collect();

        if ($customerId) {
            $customerRules = CustomerProductCatalog::query()
                ->with('productCatalog.item', 'productCatalog.category')
                ->where('customer_id', $customerId)
                ->where('is_active', true)
                ->whereHas('productCatalog', fn ($query) => $query->where('is_visible', true))
                ->get();
        }

        if ($customerRules->isNotEmpty()) {
            $products = $customerRules
                ->map(function (CustomerProductCatalog $rule) use ($publicImageUrl, $erpItemPrice, $priceList, $canViewPrice) {
                    $catalog = $rule->productCatalog;
                    $item = $catalog->item;

                    $price = $item && $canViewPrice ? $erpItemPrice($item->item_code, $item->stock_uom, $priceList) : null;

                    return [
                        'id' => $catalog->id,
                        'name' => $catalog->display_name ?: $catalog->item_name,
                        'category' => $catalog->category?->name ?: $item?->item_group ?: 'Produk',
                        'grade' => $catalog->display_description ?: $item?->brand ?: 'Fresh',
                        'uom' => $item?->stock_uom ?: 'Unit',
                        'price' => $price,
                        'price_list' => $priceList,
                        'can_view_price' => $canViewPrice,
                        'stock' => (float) $rule->daily_quantity,
                        'stock_status' => ((float) $rule->daily_quantity) > 0 ? 'full' : 'empty',
                        'image' => $publicImageUrl($catalog->display_image_url ?: $item?->image_url),
                        'daily_quantity' => (float) $rule->daily_quantity,
                        'minimum_qty' => (float) ($rule->minimum_qty ?: $catalog->minimum_qty),
                        'maximum_qty' => (float) ($rule->maximum_qty ?: $rule->daily_quantity ?: $catalog->maximum_qty),
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
            'products' => $items->map(function ($item) use ($publicImageUrl, $erpItemPrice, $priceList, $canViewPrice) {
                $price = $canViewPrice ? $erpItemPrice($item->item_code, $item->stock_uom, $priceList) : null;

                return [
                    'id' => $item->id,
                    'name' => $item->catalog->display_name ?: $item->item_name,
                    'category' => $item->catalog->category?->name ?: $item->item_group ?: 'Produk',
                    'grade' => $item->catalog->display_description ?: $item->brand ?: 'Fresh',
                    'uom' => $item->stock_uom ?: 'Unit',
                    'price' => $price,
                    'price_list' => $priceList,
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
    Route::post('/cart/submit', fn () => redirect()->route('buyer.orders'))->name('cart.submit');
    Route::get('/orders', fn () => Inertia::render('Buyer/Orders', ['orders' => []]))->name('orders');

    Route::get('/rewards', fn () => Inertia::render('Buyer/Rewards', [
        'points' => 0,
        'tier' => 'Bronze',
        'next_tier' => 'Silver',
        'next_points' => 500,
        'rewards' => [],
        'history' => [],
    ]))->name('rewards');

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
