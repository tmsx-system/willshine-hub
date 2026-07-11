<?php

namespace App\Services\ERP;

use App\Models\CustomerType;
use App\Models\ErpCustomer;
use Exception;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    public const DEFAULT_CUSTOMER_TYPES = [
        'HORECA',
        'GT',
        'MT',
        'MTI',
        'RETAIL',
        'RETAIL-ECOMMERCE',
        'DISTRIBUTOR',
        'CONSUMER',
        'MITRA',
        'CASH',
    ];

    protected FrappeClient $client;

    public function __construct(FrappeClient $client)
    {
        $this->client = $client;
    }

    public static function seedDefaultCustomerTypes(): void
    {
        foreach (self::DEFAULT_CUSTOMER_TYPES as $code) {
            CustomerType::updateOrCreate(
                ['code' => $code],
                [
                    'name' => self::formatTypeName($code),
                    'description' => "Customer type synced for {$code}.",
                    'minimum_order_amount' => 0,
                    'minimum_order_qty' => 1,
                    'allow_reward' => true,
                    'allow_promo' => true,
                    'is_active' => true,
                ],
            );
        }
    }

    public function syncCustomerTypes(): bool
    {
        self::seedDefaultCustomerTypes();

        try {
            $groups = $this->client->get('Customer Group', [
                'fields' => '["name", "customer_group_name", "modified"]',
                'limit_page_length' => 500,
            ]);

            foreach ($groups as $group) {
                $code = $this->normalizeTypeCode($group['customer_group_name'] ?? $group['name'] ?? '');

                if (!in_array($code, self::DEFAULT_CUSTOMER_TYPES, true)) {
                    continue;
                }

                CustomerType::updateOrCreate(
                    ['code' => $code],
                    [
                        'name' => self::formatTypeName($code),
                        'description' => 'Customer type matched from ERPNext Customer Group.',
                        'minimum_order_amount' => 0,
                        'minimum_order_qty' => 1,
                        'allow_reward' => true,
                        'allow_promo' => true,
                        'is_active' => true,
                    ],
                );
            }

            return true;
        } catch (Exception $e) {
            Log::warning('Customer type sync used local defaults because ERPNext Customer Group could not be fetched: ' . $e->getMessage());

            return true;
        }
    }

    public function syncCustomers()
    {
        try {
            $this->syncCustomerTypes();

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
                    $customerType = $this->resolveCustomerType($data['customer_type'] ?? null, $data['customer_group'] ?? null);

                    ErpCustomer::updateOrCreate(
                        ['erp_customer_id' => $data['name']],
                        [
                            'customer_code' => $data['name'],
                            'customer_name' => $data['customer_name'] ?? $data['name'],
                            'customer_group' => $data['customer_group'] ?? null,
                            'territory' => $data['territory'] ?? null,
                            'customer_type_id' => $customerType?->id,
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

    private function resolveCustomerType(?string $customerType, ?string $customerGroup): ?CustomerType
    {
        foreach ([$customerType, $customerGroup] as $value) {
            $code = $this->normalizeTypeCode($value ?? '');

            if ($code === '') {
                continue;
            }

            $type = CustomerType::query()
                ->where('code', $code)
                ->orWhere('name', self::formatTypeName($code))
                ->first();

            if ($type) {
                return $type;
            }
        }

        return null;
    }

    private function normalizeTypeCode(string $value): string
    {
        $normalized = strtoupper(trim(str_replace(['_', '/', '&'], ' ', $value)));
        $normalized = preg_replace('/\s+/', ' ', $normalized) ?: '';
        $compact = str_replace([' ', '-'], '', $normalized);

        return match ($compact) {
            'GENERALTRADE' => 'GT',
            'MODERNTRADE' => 'MT',
            'MODERNTRADEINDEPENDENT', 'MODERNTRADEINDEPENDENCE' => 'MTI',
            'RETAILECOMMERCE', 'RETAILECOM' => 'RETAIL-ECOMMERCE',
            default => str_replace(' ', '-', $normalized),
        };
    }

    private static function formatTypeName(string $code): string
    {
        return match ($code) {
            'HORECA' => 'HORECA',
            'GT' => 'General Trade',
            'MT' => 'Modern Trade',
            'MTI' => 'Modern Trade Independent',
            'RETAIL-ECOMMERCE' => 'Retail E-Commerce',
            default => ucwords(strtolower(str_replace('-', ' ', $code))),
        };
    }
}
