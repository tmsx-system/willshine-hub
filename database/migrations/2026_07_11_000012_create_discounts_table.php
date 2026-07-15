<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->string('discount_type')->default('percentage');
            $table->decimal('discount_value', 15, 2)->default(0);
            $table->decimal('minimum_order_amount', 15, 2)->nullable();
            $table->decimal('maximum_discount_amount', 15, 2)->nullable();
            $table->string('scope')->default('order');
            $table->foreignId('product_catalog_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_account_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->unsignedInteger('per_customer_limit')->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_stackable')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['scope', 'is_active']);
            $table->index(['starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
