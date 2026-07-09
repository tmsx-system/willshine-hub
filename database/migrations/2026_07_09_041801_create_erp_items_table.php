<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('erp_items', function (Blueprint $table) {
            $table->id();
            $table->string('erp_item_id')->unique();
            $table->string('item_code')->unique();
            $table->string('item_name');
            $table->string('item_group')->nullable();
            $table->string('stock_uom')->nullable();
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('image_url')->nullable();
            $table->string('website_image_url')->nullable();
            $table->boolean('is_stock_item')->default(true);
            $table->boolean('disabled')->default(false);
            $table->boolean('has_batch_no')->default(false);
            $table->boolean('has_serial_no')->default(false);
            $table->timestamp('erp_modified_at')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erp_items');
    }
};
