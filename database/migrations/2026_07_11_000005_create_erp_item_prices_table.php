<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('erp_item_prices', function (Blueprint $table) {
            $table->id();
            $table->string('erp_price_id')->unique();
            $table->string('item_code')->index();
            $table->string('price_list')->index();
            $table->decimal('price_list_rate', 18, 6)->default(0);
            $table->string('currency')->nullable();
            $table->string('uom')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_upto')->nullable();
            $table->timestamp('erp_modified_at')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->index(['item_code', 'price_list', 'uom'], 'erp_item_prices_item_price_list_uom_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erp_item_prices');
    }
};
