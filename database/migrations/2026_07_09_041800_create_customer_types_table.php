<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('default_price_list')->nullable();
            $table->string('default_warehouse')->nullable();
            $table->decimal('minimum_order_amount', 15, 2)->default(0);
            $table->integer('minimum_order_qty')->default(0);
            $table->boolean('allow_reward')->default(true);
            $table->boolean('allow_promo')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_types');
    }
};
