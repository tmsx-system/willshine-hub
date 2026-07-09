<?php

namespace App\Services\ERP;

use App\Models\ErpCustomer;
use Exception;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    protected FrappeClient $client;

    public function __construct(FrappeClient $client)
    {
        $this->client = $client;
    }

    public function syncCustomers()
    {
        try {
            $limit = 100;
            $start = 0;
            $hasMore = true;

            while ($hasMore) {
                // Adjust query fields based on Frappe's actual doctype structure
                $query = [
                    'fields' => '["name", "customer_name", "customer_group", "territory", "customer_type", "default_price_list", "disabled", "modified"]',
                    'limit_page_length' => $limit,
                    'limit_start' => $start,
                ];

                $customers = $this->client->get('Customer', $query);

                if (empty($customers)) {
                    $hasMore = false;
                    break;
                }

                foreach ($customers as $data) {
                    ErpCustomer::updateOrCreate(
                        ['erp_customer_id' => $data['name']],
                        [
                            'customer_code' => $data['name'],
                            'customer_name' => $data['customer_name'] ?? $data['name'],
                            'customer_group' => $data['customer_group'] ?? null,
                            'territory' => $data['territory'] ?? null,
                            // 'customer_type_id' => mapping could be done here if needed
                            'default_price_list' => $data['default_price_list'] ?? null,
                            'disabled' => $data['disabled'] ?? false,
                            'erp_modified_at' => isset($data['modified']) ? \Carbon\Carbon::parse($data['modified']) : null,
                            'last_synced_at' => now(),
                        ]
                    );
                }

                $start += $limit;
                if (count($customers) < $limit) {
                    $hasMore = false;
                }
            }

            // Update the last sync timestamp in settings
            $setting = $this->client->getSetting();
            if ($setting) {
                $setting->update(['last_sync_customer' => now()]);
            }

            return true;
        } catch (Exception $e) {
            Log::error("Failed to sync customers: " . $e->getMessage());
            throw $e;
        }
    }
}
