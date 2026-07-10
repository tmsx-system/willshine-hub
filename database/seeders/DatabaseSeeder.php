<?php

namespace Database\Seeders;

use App\Models\CustomerType;
use App\Models\ErpCustomer;
use App\Models\ErpItem;
use App\Models\ProductCatalog;
use App\Models\ProductCategory;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if ($email && $password) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => env('ADMIN_NAME', 'Administrator'),
                    'password' => bcrypt($password),
                ],
            );
        }

        $retail = CustomerType::updateOrCreate(
            ['code' => 'RETAIL'],
            [
                'name' => 'Retail Buyer',
                'description' => 'Sample customer type for small shops and individual buyers.',
                'default_price_list' => 'Standard Selling',
                'default_warehouse' => 'Stores - WSH',
                'minimum_order_amount' => 0,
                'minimum_order_qty' => 1,
                'allow_reward' => true,
                'allow_promo' => true,
                'is_active' => true,
            ],
        );

        $wholesale = CustomerType::updateOrCreate(
            ['code' => 'WHOLESALE'],
            [
                'name' => 'Wholesale Buyer',
                'description' => 'Sample customer type for bulk fruit orders.',
                'default_price_list' => 'Wholesale Selling',
                'default_warehouse' => 'Stores - WSH',
                'minimum_order_amount' => 500000,
                'minimum_order_qty' => 10,
                'allow_reward' => false,
                'allow_promo' => true,
                'is_active' => true,
            ],
        );

        ErpCustomer::updateOrCreate(
            ['erp_customer_id' => 'CUST-SAMPLE-001'],
            [
                'customer_code' => 'CUST-SAMPLE-001',
                'customer_name' => 'Toko Buah Sejahtera',
                'customer_group' => 'Retail',
                'territory' => 'Indonesia',
                'customer_type_id' => $retail->id,
                'default_price_list' => 'Standard Selling',
                'default_warehouse' => 'Stores - WSH',
                'credit_limit' => 2500000,
                'disabled' => false,
                'erp_modified_at' => now(),
                'last_synced_at' => now(),
            ],
        );

        ErpCustomer::updateOrCreate(
            ['erp_customer_id' => 'CUST-SAMPLE-002'],
            [
                'customer_code' => 'CUST-SAMPLE-002',
                'customer_name' => 'Distributor Buah Nusantara',
                'customer_group' => 'Wholesale',
                'territory' => 'Indonesia',
                'customer_type_id' => $wholesale->id,
                'default_price_list' => 'Wholesale Selling',
                'default_warehouse' => 'Stores - WSH',
                'credit_limit' => 15000000,
                'disabled' => false,
                'erp_modified_at' => now(),
                'last_synced_at' => now(),
            ],
        );

        $categories = [
            'buah-segar' => ProductCategory::updateOrCreate(
                ['slug' => 'buah-segar'],
                [
                    'name' => 'Buah Segar',
                    'description' => 'Contoh kategori untuk produk buah segar harian.',
                    'image_url' => 'images/hero_fruits.png',
                    'display_order' => 1,
                    'is_active' => true,
                ],
            ),
            'buah-premium' => ProductCategory::updateOrCreate(
                ['slug' => 'buah-premium'],
                [
                    'name' => 'Buah Premium',
                    'description' => 'Contoh kategori untuk produk buah pilihan dan impor.',
                    'image_url' => 'images/honey_mango.png',
                    'display_order' => 2,
                    'is_active' => true,
                ],
            ),
        ];

        $sampleProducts = [
            [
                'erp_item_id' => 'ITEM-SAMPLE-BANANA',
                'item_code' => 'PISANG-CAV-001',
                'item_name' => 'Pisang Cavendish',
                'item_group' => 'Fruits',
                'stock_uom' => 'Kg',
                'description' => 'Pisang Cavendish matang segar untuk contoh katalog buyer.',
                'brand' => 'Willshine Fresh',
                'image_url' => 'images/banana_organic.png',
                'website_image_url' => 'images/banana_organic.png',
                'category' => $categories['buah-segar'],
                'display_order' => 1,
                'minimum_qty' => 1,
                'maximum_qty' => 50,
                'featured' => true,
            ],
            [
                'erp_item_id' => 'ITEM-SAMPLE-GRAPE',
                'item_code' => 'ANGGUR-MERAH-001',
                'item_name' => 'Anggur Merah',
                'item_group' => 'Fruits',
                'stock_uom' => 'Kg',
                'description' => 'Anggur merah manis dengan kemasan rapi untuk pesanan retail.',
                'brand' => 'Willshine Fresh',
                'image_url' => 'images/hero_fruits.png',
                'website_image_url' => 'images/hero_fruits.png',
                'category' => $categories['buah-premium'],
                'display_order' => 2,
                'minimum_qty' => 1,
                'maximum_qty' => 30,
                'featured' => true,
            ],
            [
                'erp_item_id' => 'ITEM-SAMPLE-MANGO',
                'item_code' => 'MANGGA-MADU-001',
                'item_name' => 'Mangga Madu',
                'item_group' => 'Fruits',
                'stock_uom' => 'Kg',
                'description' => 'Mangga madu harum dan manis sebagai contoh produk unggulan.',
                'brand' => 'Willshine Fresh',
                'image_url' => 'images/honey_mango.png',
                'website_image_url' => 'images/honey_mango.png',
                'category' => $categories['buah-premium'],
                'display_order' => 3,
                'minimum_qty' => 1,
                'maximum_qty' => 40,
                'featured' => true,
            ],
            [
                'erp_item_id' => 'ITEM-SAMPLE-DRAGON-FRUIT',
                'item_code' => 'BUAH-NAGA-001',
                'item_name' => 'Buah Naga Merah',
                'item_group' => 'Fruits',
                'stock_uom' => 'Kg',
                'description' => 'Buah naga merah segar untuk contoh tampilan produk katalog.',
                'brand' => 'Willshine Fresh',
                'image_url' => 'images/dragon_fruit.png',
                'website_image_url' => 'images/dragon_fruit.png',
                'category' => $categories['buah-segar'],
                'display_order' => 4,
                'minimum_qty' => 1,
                'maximum_qty' => 40,
                'featured' => false,
            ],
            [
                'erp_item_id' => 'ITEM-SAMPLE-APPLE',
                'item_code' => 'APEL-FUJI-001',
                'item_name' => 'Apel Fuji',
                'item_group' => 'Fruits',
                'stock_uom' => 'Kg',
                'description' => 'Apel Fuji renyah untuk contoh data katalog produk.',
                'brand' => 'Willshine Fresh',
                'image_url' => 'images/apple_fuji.png',
                'website_image_url' => 'images/apple_fuji.png',
                'category' => $categories['buah-premium'],
                'display_order' => 5,
                'minimum_qty' => 1,
                'maximum_qty' => 30,
                'featured' => false,
            ],
        ];

        foreach ($sampleProducts as $sampleProduct) {
            $item = ErpItem::updateOrCreate(
                ['erp_item_id' => $sampleProduct['erp_item_id']],
                [
                    'item_code' => $sampleProduct['item_code'],
                    'item_name' => $sampleProduct['item_name'],
                    'item_group' => $sampleProduct['item_group'],
                    'stock_uom' => $sampleProduct['stock_uom'],
                    'description' => $sampleProduct['description'],
                    'brand' => $sampleProduct['brand'],
                    'image_url' => $sampleProduct['image_url'],
                    'website_image_url' => $sampleProduct['website_image_url'],
                    'is_stock_item' => true,
                    'disabled' => false,
                    'has_batch_no' => false,
                    'has_serial_no' => false,
                    'erp_modified_at' => now(),
                    'last_synced_at' => now(),
                ],
            );

            ProductCatalog::updateOrCreate(
                ['item_id' => $item->id],
                [
                    'item_code' => $item->item_code,
                    'item_name' => $item->item_name,
                    'category_id' => $sampleProduct['category']->id,
                    'display_name' => $item->item_name,
                    'display_description' => $item->description,
                    'display_image_url' => $item->website_image_url ?: $item->image_url,
                    'is_visible' => true,
                    'is_featured' => $sampleProduct['featured'],
                    'display_order' => $sampleProduct['display_order'],
                    'minimum_qty' => $sampleProduct['minimum_qty'],
                    'maximum_qty' => $sampleProduct['maximum_qty'],
                    'allow_decimal_qty' => false,
                    'show_stock' => true,
                    'show_price' => true,
                ],
            );
        }
    }
}
