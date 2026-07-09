<?php

namespace App\Services\ERP;

use App\Models\ErpItem;
use Exception;
use Illuminate\Support\Facades\Log;

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
            $limit = 100;
            $start = 0;
            $hasMore = true;

            while ($hasMore) {
                $query = [
                    'fields' => '["name", "item_code", "item_name", "item_group", "stock_uom", "description", "brand", "image", "website_image", "is_stock_item", "disabled", "has_batch_no", "has_serial_no", "modified"]',
                    'limit_page_length' => $limit,
                    'limit_start' => $start,
                ];

                $items = $this->client->get('Item', $query);

                if (empty($items)) {
                    $hasMore = false;
                    break;
                }

                foreach ($items as $data) {
                    $websiteImageUrl = null;
                    if (!empty($data['website_image'])) {
                        $baseUrl = rtrim($this->client->getSetting()->erp_site_url, '/');
                        $websiteImageUrl = str_starts_with($data['website_image'], 'http') ? $data['website_image'] : $baseUrl . $data['website_image'];
                    }
                    
                    $imageUrl = null;
                    if (!empty($data['image'])) {
                        $baseUrl = rtrim($this->client->getSetting()->erp_site_url, '/');
                        $imageUrl = str_starts_with($data['image'], 'http') ? $data['image'] : $baseUrl . $data['image'];
                    }

                    ErpItem::updateOrCreate(
                        ['erp_item_id' => $data['name']],
                        [
                            'item_code' => $data['item_code'] ?? $data['name'],
                            'item_name' => $data['item_name'] ?? $data['name'],
                            'item_group' => $data['item_group'] ?? null,
                            'stock_uom' => $data['stock_uom'] ?? null,
                            'description' => $data['description'] ?? null,
                            'brand' => $data['brand'] ?? null,
                            'image_url' => $imageUrl,
                            'website_image_url' => $websiteImageUrl,
                            'is_stock_item' => $data['is_stock_item'] ?? true,
                            'disabled' => $data['disabled'] ?? false,
                            'has_batch_no' => $data['has_batch_no'] ?? false,
                            'has_serial_no' => $data['has_serial_no'] ?? false,
                            'erp_modified_at' => isset($data['modified']) ? \Carbon\Carbon::parse($data['modified']) : null,
                            'last_synced_at' => now(),
                        ]
                    );
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
}
