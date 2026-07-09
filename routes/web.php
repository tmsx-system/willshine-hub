<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Auth\AuthController;

/* ── Public ─────────────────────────────────── */

Route::get('/', function () {
    return Inertia::render('Landing');
})->name('landing');

Route::get('/products', function () {
    return Inertia::render('PublicCatalog');
})->name('public.products');

Route::get('/public-rewards', function () {
    return Inertia::render('PublicRewards');
})->name('public.rewards');

/* ── Auth ────────────────────────────────────── */

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return Inertia::render('Auth/Login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* ── Buyer Portal ─────────────────────────────── */

Route::prefix('buyer')->name('buyer.')->middleware('auth')->group(function () {

    Route::get('/', function () {
        return Inertia::render('Buyer/Dashboard', [
            'buyer' => [
                'name'    => 'PT Berkah Mandiri',
                'contact' => 'Bapak Hendra Wijaya',
                'email'   => 'hendra@berkah.co.id',
                'tier'    => 'Gold',
                'avatar'  => 'PB',
            ],
            'stats' => [
                'stock_allocation' => 2450,
                'pending_orders'   => 3,
                'total_spend'      => 48750000,
                'reward_points'    => 1850,
            ],
            'recent_orders' => [
                ['id' => 'WH-2024-0098', 'date' => '2024-07-06', 'items' => 12, 'total' => 8500000,  'status' => 'Invoiced'],
                ['id' => 'WH-2024-0097', 'date' => '2024-07-04', 'items' => 8,  'total' => 5200000,  'status' => 'Approved'],
                ['id' => 'WH-2024-0096', 'date' => '2024-07-03', 'items' => 5,  'total' => 3100000,  'status' => 'Pending'],
                ['id' => 'WH-2024-0095', 'date' => '2024-07-01', 'items' => 20, 'total' => 14200000, 'status' => 'Invoiced'],
                ['id' => 'WH-2024-0094', 'date' => '2024-06-29', 'items' => 3,  'total' => 1950000,  'status' => 'Pending'],
            ],
        ]);
    })->name('dashboard');

    Route::get('/catalog', function () {
        return Inertia::render('Buyer/Catalog', [
            'categories' => ['Semua', 'Buah Segar', 'Bibit Tanaman', 'Benih Premium', 'Peralatan Berkebun'],
            'products' => [
                ['id' => 1, 'name' => 'Premium Fuji Apple',    'category' => 'Buah Segar', 'grade' => 'Grade AAA / Import',  'uom' => 'Kg',  'price' => 45000,  'stock' => 850,  'stock_status' => 'full',  'image_seed' => 'apple'],
                ['id' => 2, 'name' => 'Organic Banana',        'category' => 'Buah Segar', 'grade' => 'Cavendish Local',     'uom' => 'Kg',  'price' => 18500,  'stock' => 45,   'stock_status' => 'low',   'image_seed' => 'banana'],
                ['id' => 3, 'name' => 'Dragon Fruit',          'category' => 'Buah Segar', 'grade' => 'Super Red / Local',   'uom' => 'Kg',  'price' => 25000,  'stock' => 320,  'stock_status' => 'full',  'image_seed' => 'dragon_fruit'],
                ['id' => 4, 'name' => 'Honey Mango',           'category' => 'Buah Segar', 'grade' => 'Probolinggo Export',  'uom' => 'Kg',  'price' => 35000,  'stock' => 0,    'stock_status' => 'empty', 'image_seed' => 'mango'],
                ['id' => 5, 'name' => 'Bibit Alpukat Miki',    'category' => 'Bibit Tanaman', 'grade' => 'Tinggi 50-70cm',   'uom' => 'Pohon','price' => 45000,  'stock' => 120,  'stock_status' => 'full',  'image_seed' => 'avocado_tree'],
                ['id' => 6, 'name' => 'Benih Tomat Cherry',    'category' => 'Benih Premium', 'grade' => 'F1 Hybrid / Pack', 'uom' => 'Pack', 'price' => 15000,  'stock' => 500,  'stock_status' => 'full',  'image_seed' => 'tomato_seeds'],
                ['id' => 7, 'name' => 'Bibit Mangga Irwin',    'category' => 'Bibit Tanaman', 'grade' => 'Tinggi 60cm',      'uom' => 'Pohon','price' => 65000,  'stock' => 20,   'stock_status' => 'low',   'image_seed' => 'mango_tree'],
                ['id' => 8, 'name' => 'Benih Selada Air',      'category' => 'Benih Premium', 'grade' => 'Organik / Pack',   'uom' => 'Pack', 'price' => 12000,  'stock' => 0,    'stock_status' => 'empty', 'image_seed' => 'lettuce_seeds'],
            ],
        ]);
    })->name('catalog');

    Route::get('/cart', function () {
        return Inertia::render('Buyer/Cart');
    })->name('cart');

    Route::post('/cart/submit', function () {
        return redirect()->route('buyer.orders');
    })->name('cart.submit');

    Route::get('/orders', function () {
        return Inertia::render('Buyer/Orders', [
            'orders' => [
                ['id' => 'WH-2024-0098', 'date' => '2024-07-06', 'items' => 12, 'total' => 8500000,  'status' => 'Invoiced',   'payment' => 'Transfer Bank'],
                ['id' => 'WH-2024-0097', 'date' => '2024-07-04', 'items' => 8,  'total' => 5200000,  'status' => 'Approved',   'payment' => 'Transfer Bank'],
                ['id' => 'WH-2024-0096', 'date' => '2024-07-03', 'items' => 5,  'total' => 3100000,  'status' => 'Pending',    'payment' => 'Tempo 30 Hari'],
                ['id' => 'WH-2024-0095', 'date' => '2024-07-01', 'items' => 20, 'total' => 14200000, 'status' => 'Invoiced',   'payment' => 'Transfer Bank'],
                ['id' => 'WH-2024-0094', 'date' => '2024-06-29', 'items' => 3,  'total' => 1950000,  'status' => 'Pending',    'payment' => 'Tempo 30 Hari'],
                ['id' => 'WH-2024-0093', 'date' => '2024-06-27', 'items' => 15, 'total' => 11500000, 'status' => 'Invoiced',   'payment' => 'Transfer Bank'],
                ['id' => 'WH-2024-0092', 'date' => '2024-06-25', 'items' => 7,  'total' => 4800000,  'status' => 'Rejected',   'payment' => 'Transfer Bank'],
                ['id' => 'WH-2024-0091', 'date' => '2024-06-22', 'items' => 9,  'total' => 6300000,  'status' => 'Invoiced',   'payment' => 'Tempo 30 Hari'],
                ['id' => 'WH-2024-0090', 'date' => '2024-06-20', 'items' => 4,  'total' => 2750000,  'status' => 'Approved',   'payment' => 'Transfer Bank'],
                ['id' => 'WH-2024-0089', 'date' => '2024-06-18', 'items' => 11, 'total' => 9100000,  'status' => 'Invoiced',   'payment' => 'Transfer Bank'],
            ],
        ]);
    })->name('orders');

    Route::get('/rewards', function () {
        return Inertia::render('Buyer/Rewards', [
            'points'       => 1850,
            'tier'         => 'Gold',
            'next_tier'    => 'Platinum',
            'next_points'  => 2500,
            'rewards' => [
                ['id' => 1, 'name' => 'Diskon 5%',         'points' => 500,  'category' => 'Discount', 'valid' => '2024-12-31'],
                ['id' => 2, 'name' => 'Free Ongkir',        'points' => 300,  'category' => 'Shipping', 'valid' => '2024-10-31'],
                ['id' => 3, 'name' => 'Diskon 10%',         'points' => 1000, 'category' => 'Discount', 'valid' => '2024-12-31'],
                ['id' => 4, 'name' => 'Hadiah Hamper',       'points' => 2000, 'category' => 'Gift',     'valid' => '2024-12-31'],
                ['id' => 5, 'name' => 'Cashback Rp 100rb',  'points' => 800,  'category' => 'Cashback', 'valid' => '2024-11-30'],
                ['id' => 6, 'name' => 'Priority Order',      'points' => 1500, 'category' => 'Service',  'valid' => '2024-12-31'],
            ],
            'history' => [
                ['date' => '2024-07-06', 'description' => 'Pembelian Order WH-2024-0098', 'points' => 850,  'type' => 'earn'],
                ['date' => '2024-07-04', 'description' => 'Pembelian Order WH-2024-0097', 'points' => 520,  'type' => 'earn'],
                ['date' => '2024-07-01', 'description' => 'Redeem Diskon 5%',             'points' => -500, 'type' => 'redeem'],
                ['date' => '2024-07-01', 'description' => 'Pembelian Order WH-2024-0095', 'points' => 1420, 'type' => 'earn'],
                ['date' => '2024-06-29', 'description' => 'Pembelian Order WH-2024-0094', 'points' => 195,  'type' => 'earn'],
                ['date' => '2024-06-27', 'description' => 'Redeem Free Ongkir',           'points' => -300, 'type' => 'redeem'],
                ['date' => '2024-06-27', 'description' => 'Pembelian Order WH-2024-0093', 'points' => 1150, 'type' => 'earn'],
                ['date' => '2024-06-22', 'description' => 'Bonus Tier Gold Achieved',     'points' => 200,  'type' => 'bonus'],
            ],
        ]);
    })->name('rewards');

    Route::get('/settings', function () {
        return Inertia::render('Buyer/Settings', [
            'buyer' => [
                'name'    => 'PT Berkah Mandiri',
                'contact' => 'Hendra Wijaya',
                'email'   => 'hendra@berkah.co.id',
                'phone'   => '08119876543',
                'address' => 'Jl. Raya Serpong No. 12, BSD City, Tangerang Selatan',
                'npwp'    => '01.234.567.8-901.000',
                'tier'    => 'Gold',
            ],
        ]);
    })->name('settings');
});