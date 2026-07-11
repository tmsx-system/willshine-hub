<?php

use App\Http\Controllers\Auth\AuthController;
use App\Models\CustomerProductCatalog;
use App\Models\ErpSetting;
use App\Models\ErpItem;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
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

$publicRewards = fn () => [
    [
        'id' => 1,
        'name' => 'Gratis Ongkir',
        'points' => '350 poin',
        'desc' => 'Tukar poin untuk bebas biaya pengiriman pesanan berikutnya.',
        'icon' => 'M8 7h8m-8 5h8m-8 5h5M5 7h.01M5 12h.01M5 17h.01',
    ],
    [
        'id' => 2,
        'name' => 'Diskon Belanja 5%',
        'points' => '500 poin',
        'desc' => 'Potongan untuk pembelian katalog buah segar pilihan.',
        'icon' => 'M9 14l6-6m-5.5.5h.01m5 5h.01M19 5L5 19',
    ],
    [
        'id' => 3,
        'name' => 'Bonus Produk',
        'points' => '750 poin',
        'desc' => 'Dapatkan tambahan produk contoh untuk pesanan tertentu.',
        'icon' => 'M20 12v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7m16 0H4m16 0a2 2 0 100-4H4a2 2 0 100 4m8-4v13m0-13V6a2 2 0 114 0c0 2-4 2-4 2zm0 0V6a2 2 0 10-4 0c0 2 4 2 4 2z',
    ],
];

$buyerRewards = fn () => [
    [
        'id' => 1,
        'name' => 'Gratis Ongkir Jabodetabek',
        'category' => 'Shipping',
        'points' => 350,
        'valid' => '2026-12-31',
    ],
    [
        'id' => 2,
        'name' => 'Diskon Belanja 5%',
        'category' => 'Discount',
        'points' => 500,
        'valid' => '2026-12-31',
    ],
    [
        'id' => 3,
        'name' => 'Cashback 25.000',
        'category' => 'Cashback',
        'points' => 650,
        'valid' => '2026-12-31',
    ],
    [
        'id' => 4,
        'name' => 'Bonus Pisang Cavendish 1 Kg',
        'category' => 'Gift',
        'points' => 750,
        'valid' => '2026-12-31',
    ],
    [
        'id' => 5,
        'name' => 'Prioritas Packing Pesanan',
        'category' => 'Service',
        'points' => 900,
        'valid' => '2026-12-31',
    ],
];

$rewardHistory = fn () => [
    ['type' => 'earn', 'description' => 'Bonus registrasi pelanggan', 'points' => 250, 'date' => '2026-07-01'],
    ['type' => 'earn', 'description' => 'Simulasi pembelian katalog buah', 'points' => 400, 'date' => '2026-07-05'],
    ['type' => 'redeem', 'description' => 'Contoh penukaran gratis ongkir', 'points' => -350, 'date' => '2026-07-08'],
];

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

Route::get('/rewards', fn () => Inertia::render('PublicRewards', ['rewards' => $publicRewards()]))
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

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('buyer')->name('buyer.')->middleware('auth')->group(function () use ($buyerRewards, $rewardHistory, $publicImageUrl) {
    Route::get('/', function (Request $request) {
        $user = $request->user();

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

    Route::get('/catalog', function (Request $request) use ($publicImageUrl) {
        $customerId = $request->user()->customer_id;
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
                ->map(function (CustomerProductCatalog $rule) use ($publicImageUrl) {
                    $catalog = $rule->productCatalog;
                    $item = $catalog->item;

                    return [
                        'id' => $catalog->id,
                        'name' => $catalog->display_name ?: $catalog->item_name,
                        'category' => $catalog->category?->name ?: $item?->item_group ?: 'Produk',
                        'grade' => $catalog->display_description ?: $item?->brand ?: 'Fresh',
                        'uom' => $item?->stock_uom ?: 'Unit',
                        'price' => 0,
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
            'products' => $items->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->catalog->display_name ?: $item->item_name,
                'category' => $item->catalog->category?->name ?: $item->item_group ?: 'Produk',
                'grade' => $item->catalog->display_description ?: $item->brand ?: 'Fresh',
                'uom' => $item->stock_uom ?: 'Unit',
                'price' => 0,
                'stock' => 0,
                'stock_status' => 'empty',
                'image' => $publicImageUrl($item->catalog->display_image_url ?: $item->image_url),
                'daily_quantity' => null,
                'minimum_qty' => (float) $item->catalog->minimum_qty,
                'maximum_qty' => $item->catalog->maximum_qty ? (float) $item->catalog->maximum_qty : null,
                'allocation_note' => null,
            ]),
            'uses_customer_rules' => false,
        ]);
    })->name('catalog');

    Route::get('/cart', fn () => Inertia::render('Buyer/Cart'))->name('cart');
    Route::post('/cart/submit', fn () => redirect()->route('buyer.orders'))->name('cart.submit');
    Route::get('/orders', fn () => Inertia::render('Buyer/Orders', ['orders' => []]))->name('orders');

    Route::get('/rewards', fn () => Inertia::render('Buyer/Rewards', [
        'points' => 900,
        'tier' => 'Silver',
        'next_tier' => 'Gold',
        'next_points' => 1000,
        'rewards' => $buyerRewards(),
        'history' => $rewardHistory(),
    ]))->name('rewards');

    Route::get('/settings', function (Request $request) {
        $user = $request->user();

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
});
