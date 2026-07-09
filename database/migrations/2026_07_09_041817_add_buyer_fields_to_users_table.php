<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->constrained('erp_customers')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->foreignId('customer_type_id')->nullable()->constrained('customer_types')->nullOnDelete();
            $table->string('sales_person')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('can_order')->default(true);
            $table->boolean('can_view_price')->default(true);
            $table->boolean('can_view_reward')->default(true);
            $table->timestamp('last_login_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['customer_type_id']);
            $table->dropColumn([
                'customer_id',
                'customer_name',
                'customer_type_id',
                'sales_person',
                'is_active',
                'can_order',
                'can_view_price',
                'can_view_reward',
                'last_login_at'
            ]);
        });
    }
};
