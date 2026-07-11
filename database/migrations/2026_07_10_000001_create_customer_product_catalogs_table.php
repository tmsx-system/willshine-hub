<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_product_catalogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('erp_customers')->cascadeOnDelete();
            $table->foreignId('product_catalog_id')->constrained('product_catalogs')->cascadeOnDelete();
            $table->decimal('daily_quantity', 12, 2)->default(0);
            $table->decimal('minimum_qty', 12, 2)->nullable();
            $table->decimal('maximum_qty', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['customer_id', 'product_catalog_id'], 'customer_product_catalog_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_product_catalogs');
    }
};
