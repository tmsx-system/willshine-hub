<?php

namespace App\Services\ERP;

use App\Models\ErpItem;
use App\Models\ProductCatalog;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ItemService
{
    protected FrappeClient $client;

    public function __construct(FrappeClient $client)
    {
        $this->client = $client;
    }

    public function syncItems()
    {
        try {
            $this->syncProductCategories();

            $limit = 100;
            $start = 0;
            $hasMore = true;

            while ($hasMore) {
                $query = [
                    'fields' => '["name", "item_code", "item_name", "item_group", "stock_uom", "description", "brand", "image", "is_stock_item", "disabled", "has_batch_no", "has_serial_no", "modified"]',
                    'limit_page_length' => $limit,
                    'limit_start' => $start,
                ];

                $items = $this->client->get('Item', $query);

                if (empty($items)) {
                    $hasMore = false;
                    break;
                }

                foreach ($items as $data) {
                    $imageUrl = $this->absoluteFileUrl($data['image'] ?? null);

                    $item = ErpItem::updateOrCreate(
                        ['erp_item_id' => $data['name']],
                        [
                            'item_code' => $data['item_code'] ?? $data['name'],
                            'item_name' => $data['item_name'] ?? $data['name'],
                            'item_group' => $data['item_group'] ?? null,
                            'stock_uom' => $data['stock_uom'] ?? null,
                            'description' => $data['description'] ?? null,
                            'brand' => $data['brand'] ?? null,
                            'image_url' => $imageUrl,
                            'is_stock_item' => $data['is_stock_item'] ?? true,
                            'disabled' => $data['disabled'] ?? false,
                            'has_batch_no' => $data['has_batch_no'] ?? false,
                            'has_serial_no' => $data['has_serial_no'] ?? false,
                            'erp_modified_at' => isset($data['modified']) ? \Carbon\Carbon::parse($data['modified']) : null,
                            'last_synced_at' => now(),
                        ]
                    );

                    $category = $this->resolveCategory($item->item_group);

                    $catalog = ProductCatalog::firstOrCreate(
                        ['item_id' => $item->id],
                        [
                            'item_code' => $item->item_code,
                            'item_name' => $item->item_name,
                            'category_id' => $category?->id,
                            'display_name' => $item->item_name,
                            'display_description' => $item->description,
                            'display_image_url' => $item->image_url,
                            'is_visible' => false,
                            'is_featured' => false,
                            'display_order' => 0,
                            'minimum_qty' => 1,
                            'show_stock' => true,
                            'show_price' => true,
                        ],
                    );

                    $catalog->fill([
                        'item_code' => $item->item_code,
                        'item_name' => $item->item_name,
                        'category_id' => $catalog->category_id ?: $category?->id,
                    ])->save();

                    if (!$catalog->display_image_url && $item->image_url) {
                        $catalog->forceFill(['display_image_url' => $item->image_url])->save();
                    }
                }

                $start += $limit;
                if (count($items) < $limit) {
                    $hasMore = false;
                }
            }

            // Update the last sync timestamp in settings
            $setting = $this->client->getSetting();
            if ($setting) {
                $setting->update(['last_sync_item' => now()]);
            }

            return true;
        } catch (Exception $e) {
            Log::error("Failed to sync items: " . $e->getMessage());
            throw $e;
        }
    }

    public function syncProductCategories(): bool
    {
        try {
            $groups = $this->client->get('Item Group', [
                'fields' => '["name", "item_group_name", "parent_item_group", "is_group", "modified"]',
                'limit_page_length' => 500,
            ]);

            foreach ($groups as $index => $group) {
                $name = $group['item_group_name'] ?? $group['name'] ?? null;

                if (!$name || in_array($name, ['All Item Groups', 'Products'], true)) {
                    continue;
                }

                ProductCategory::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'name' => $name,
                        'description' => 'Synced from ERPNext Item Group.',
                        'display_order' => $index + 1,
                        'is_active' => true,
                    ],
                );
            }

            return true;
        } catch (Exception $e) {
            Log::warning('Product category sync skipped because ERPNext Item Group could not be fetched: ' . $e->getMessage());

            return true;
        }
    }

    private function resolveCategory(?string $itemGroup): ?ProductCategory
    {
        if (!$itemGroup) {
            return null;
        }

        return ProductCategory::firstOrCreate(
            ['slug' => Str::slug($itemGroup)],
            [
                'name' => $itemGroup,
                'description' => 'Generated from ERPNext Item Group.',
                'display_order' => 0,
                'is_active' => true,
            ],
        );
    }

    private function absoluteFileUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $baseUrl = rtrim($this->client->getSetting()->erp_site_url, '/');

        return $baseUrl . '/' . ltrim($path, '/');
    }
}
