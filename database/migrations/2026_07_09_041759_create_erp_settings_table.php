<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('erp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('erp_site_url')->nullable();
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('default_company')->nullable();
            $table->string('default_selling_price_list')->nullable();
            $table->string('default_warehouse')->nullable();
            $table->string('default_so_naming_series')->nullable();
            $table->boolean('enable_auto_sync')->default(false);
            $table->timestamp('last_sync_customer')->nullable();
            $table->timestamp('last_sync_item')->nullable();
            $table->timestamp('last_sync_stock')->nullable();
            $table->timestamp('last_sync_price')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erp_settings');
    }
};
