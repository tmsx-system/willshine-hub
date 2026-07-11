<?php

namespace App\Services\ERP;

use App\Models\ErpItem;
use App\Models\ErpItemPrice;
use App\Models\ErpCustomer;
use App\Models\CustomerType;
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
            $setting = $this->client->getSetting();
            $company = $setting?->default_company;
            $allowedItemCodes = $this->resolveAllowedItemCodes();
            $syncedItemIds = [];

            if ($allowedItemCodes === []) {
                throw new Exception('No ERP item codes were found for the selected Default Warehouse/Company. Set Default Warehouse to a warehouse under Distribusi Jakarta, then sync again.');
            }

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
                    $itemCode = $data['item_code'] ?? $data['name'];

                    if ($allowedItemCodes !== null && !in_array($itemCode, $allowedItemCodes, true)) {
                        continue;
                    }

                    $imageUrl = $this->absoluteFileUrl($data['image'] ?? null);

                    $item = ErpItem::updateOrCreate(
                        ['erp_item_id' => $data['name']],
                        [
                            'item_code' => $itemCode,
                            'item_name' => $data['item_name'] ?? $data['name'],
                            'item_group' => $data['item_group'] ?? null,
                            'stock_uom' => $data['stock_uom'] ?? null,
                            'description' => $data['description'] ?? null,
                            'brand' => $data['brand'] ?? null,
                            'company' => $data['company'] ?? $company,
                            'image_url' => $imageUrl,
                            'is_stock_item' => $data['is_stock_item'] ?? true,
                            'disabled' => $data['disabled'] ?? false,
                            'has_batch_no' => $data['has_batch_no'] ?? false,
                            'has_serial_no' => $data['has_serial_no'] ?? false,
                            'erp_modified_at' => isset($data['modified']) ? \Carbon\Carbon::parse($data['modified']) : null,
                            'last_synced_at' => now(),
                        ]
                    );
                    $syncedItemIds[] = $item->id;

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

            if ($company && $syncedItemIds !== []) {
                ErpItem::query()
                    ->whereNotIn('id', $syncedItemIds)
                    ->update(['disabled' => true]);
            }

            // Update the last sync timestamp in settings
            if ($setting) {
                $setting->update(['last_sync_item' => now()]);
            }

            try {
                $this->syncPrices();
            } catch (Exception $priceException) {
                Log::warning('Item sync completed but price sync was skipped: ' . $priceException->getMessage());
            }

            return true;
        } catch (Exception $e) {
            Log::error("Failed to sync items: " . $e->getMessage());
            throw $e;
        }
    }

    private function resolveAllowedItemCodes(): ?array
    {
        $setting = $this->client->getSetting();
        $warehouse = $setting?->default_warehouse;
        $company = $setting?->default_company;

        if (!$warehouse && !$company) {
            return null;
        }

        $warehouses = $warehouse ? [$warehouse] : $this->warehousesForCompany($company);

        if ($warehouse || $warehouses !== []) {
            return $this->itemCodesForWarehouses($warehouses);
        }

        if ($company) {
            $companyItemCodes = $this->itemCodesForCompany($company);

            if ($companyItemCodes !== []) {
                return $companyItemCodes;
            }
        }

        if ($warehouse || $company) {
            Log::warning("Item sync skipped company filter because no warehouses were found for company: {$company}");

            return [];
        }

        return null;
    }

    private function itemCodesForWarehouses(array $warehouses): array
    {
        $codes = [];

        foreach ($warehouses as $warehouseName) {
            $start = 0;
            $limit = 500;

            do {
                $bins = $this->client->get('Bin', [
                    'fields' => '["item_code"]',
                    'filters' => json_encode([
                        ['warehouse', '=', $warehouseName],
                    ]),
                    'limit_page_length' => $limit,
                    'limit_start' => $start,
                ]);

                foreach ($bins as $bin) {
                    if (!empty($bin['item_code'])) {
                        $codes[] = $bin['item_code'];
                    }
                }

                $start += $limit;
            } while (count($bins) === $limit);
        }

        return array_values(array_unique($codes));
    }

    public function syncPrices(): bool
    {
        try {
            $setting = $this->client->getSetting();
            $priceLists = collect([
                $setting?->default_selling_price_list,
                ...ErpCustomer::query()
                    ->whereNotNull('default_price_list')
                    ->pluck('default_price_list')
                    ->all(),
                ...CustomerType::query()
                    ->whereNotNull('default_price_list')
                    ->pluck('default_price_list')
                    ->all(),
            ])
                ->filter()
                ->unique()
                ->values();

            if ($priceLists->isEmpty()) {
                throw new Exception('No price list found. Set Default Selling Price List in ERP Settings or sync customers with Default Price List first.');
            }

            $syncedPriceIds = [];

            foreach ($priceLists as $priceList) {
                $start = 0;
                $limit = 500;

                do {
                    $prices = $this->client->get('Item Price', [
                        'fields' => '["name", "item_code", "price_list", "price_list_rate", "currency", "uom", "valid_from", "valid_upto", "modified"]',
                        'filters' => json_encode([
                            ['price_list', '=', $priceList],
                            ['selling', '=', 1],
                        ]),
                        'limit_page_length' => $limit,
                        'limit_start' => $start,
                    ]);

                    foreach ($prices as $data) {
                        if (empty($data['item_code']) || empty($data['price_list'])) {
                            continue;
                        }

                        $price = ErpItemPrice::updateOrCreate(
                            ['erp_price_id' => $data['name']],
                            [
                                'item_code' => $data['item_code'],
                                'price_list' => $data['price_list'],
                                'price_list_rate' => $data['price_list_rate'] ?? 0,
                                'currency' => $data['currency'] ?? null,
                                'uom' => $data['uom'] ?? null,
                                'valid_from' => $data['valid_from'] ?? null,
                                'valid_upto' => $data['valid_upto'] ?? null,
                                'erp_modified_at' => isset($data['modified']) ? \Carbon\Carbon::parse($data['modified']) : null,
                                'last_synced_at' => now(),
                            ],
                        );

                        $syncedPriceIds[] = $price->id;
                    }

                    $start += $limit;
                } while (count($prices) === $limit);
            }

            if ($setting) {
                $setting->update(['last_sync_price' => now()]);
            }

            return true;
        } catch (Exception $e) {
            Log::error("Failed to sync item prices: " . $e->getMessage());
            throw $e;
        }
    }

    private function itemCodesForCompany(string $company): array
    {
        try {
            $codes = [];
            $start = 0;
            $limit = 500;

            do {
                $items = $this->client->get('Item', [
                    'fields' => '["name", "item_code"]',
                    'filters' => json_encode([
                        ['company', '=', $company],
                    ]),
                    'limit_page_length' => $limit,
                    'limit_start' => $start,
                ]);

                foreach ($items as $item) {
                    $codes[] = $item['item_code'] ?? $item['name'];
                }

                $start += $limit;
            } while (count($items) === $limit);

            return array_values(array_unique(array_filter($codes)));
        } catch (Exception $exception) {
            Log::warning("Item company filter failed for {$company}; falling back to warehouse/Bin lookup: " . $exception->getMessage());

            return [];
        }
    }

    private function warehousesForCompany(?string $company): array
    {
        if (!$company) {
            return [];
        }

        $warehouses = $this->client->get('Warehouse', [
            'fields' => '["name"]',
            'filters' => json_encode([
                ['company', '=', $company],
                ['is_group', '=', 0],
            ]),
            'limit_page_length' => 500,
        ]);

        return collect($warehouses)
            ->pluck('name')
            ->filter()
            ->values()
            ->all();
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
