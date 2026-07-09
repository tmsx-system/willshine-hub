<?php

namespace App\Jobs;

use App\Services\ERP\ItemService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncErpItemsJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function handle(ItemService $service): void
    {
        try {
            Log::info("Starting ERP Items sync via queue.");
            $service->syncItems();
            Log::info("Successfully synced ERP Items.");
        } catch (\Exception $e) {
            Log::error("ERP Items sync job failed: " . $e->getMessage());
            throw $e;
        }
    }
}
