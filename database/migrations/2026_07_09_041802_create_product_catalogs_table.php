<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_catalogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('erp_items')->cascadeOnDelete();
            $table->string('item_code')->nullable();
            $table->string('item_name')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('display_name')->nullable();
            $table->text('display_description')->nullable();
            $table->string('display_image_url')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('display_order')->default(0);
            $table->integer('minimum_qty')->default(1);
            $table->integer('maximum_qty')->nullable();
            $table->boolean('allow_decimal_qty')->default(false);
            $table->boolean('show_stock')->default(true);
            $table->boolean('show_price')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_catalogs');
    }
};
