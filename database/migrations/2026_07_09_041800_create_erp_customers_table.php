<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('erp_customers', function (Blueprint $table) {
            $table->id();
            $table->string('erp_customer_id')->unique();
            $table->string('customer_code')->nullable();
            $table->string('customer_name');
            $table->string('customer_group')->nullable();
            $table->string('territory')->nullable();
            $table->foreignId('customer_type_id')->nullable()->constrained('customer_types')->nullOnDelete();
            $table->string('default_price_list')->nullable();
            $table->string('default_warehouse')->nullable();
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->boolean('disabled')->default(false);
            $table->timestamp('erp_modified_at')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erp_customers');
    }
};
