<?php

namespace App\Jobs;

use App\Services\ERP\CustomerService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncErpCustomersJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function handle(CustomerService $service): void
    {
        try {
            Log::info("Starting ERP Customers sync via queue.");
            $service->syncCustomers();
            Log::info("Successfully synced ERP Customers.");
        } catch (\Exception $e) {
            Log::error("ERP Customers sync job failed: " . $e->getMessage());
            throw $e;
        }
    }
}
