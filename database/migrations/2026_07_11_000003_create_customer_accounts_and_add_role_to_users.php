<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('erp_customers')->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('customer_name')->nullable();
            $table->foreignId('customer_type_id')->nullable()->constrained('customer_types')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('can_order')->default(true);
            $table->boolean('can_view_price')->default(true);
            $table->boolean('can_view_reward')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_accounts');

    }
};
