<?php

use App\Http\Controllers\Auth\AuthController;
use App\Models\ErpItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

$publicProducts = function (int $limit = 48) {
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
            'uom' => $item->stock_uom,
            'price' => $item->catalog->show_price ? 'Masuk untuk melihat harga' : 'Hubungi kami',
            'stock_status' => 'Tersedia',
            'image' => $item->catalog->display_image_url ?: $item->website_image_url ?: $item->image_url,
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

Route::get('/', fn () => Inertia::render('Landing', ['products' => $publicProducts(4)]))->name('landing');

Route::get('/products', function () use ($publicProducts) {
    return Inertia::render('PublicCatalog', ['products' => $publicProducts()]);
})->name('public.products');

Route::get('/public-rewards', fn () => Inertia::render('PublicRewards', ['rewards' => $publicRewards()]))
    ->name('public.rewards');

Route::get('/login', fn () => Inertia::render('Auth/Login'))->name('login');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('buyer')->name('buyer.')->middleware('auth')->group(function () use ($buyerRewards, $rewardHistory) {
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

    Route::get('/catalog', function () {
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
                'category' => $item->catalog->category?->name ?: $item->item_group,
                'grade' => $item->catalog->display_description ?: $item->brand,
                'uom' => $item->stock_uom,
                'price' => 0,
                'stock' => 0,
                'stock_status' => 'empty',
                'image' => $item->catalog->display_image_url ?: $item->website_image_url ?: $item->image_url,
            ]),
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
