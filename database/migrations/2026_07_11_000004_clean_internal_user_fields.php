<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'customer_id')) {
                $table->dropForeign(['customer_id']);
            }

            if (Schema::hasColumn('users', 'customer_type_id')) {
                $table->dropForeign(['customer_type_id']);
            }
        });

        $columns = array_values(array_filter([
            Schema::hasColumn('users', 'role') ? 'role' : null,
            Schema::hasColumn('users', 'customer_id') ? 'customer_id' : null,
            Schema::hasColumn('users', 'customer_name') ? 'customer_name' : null,
            Schema::hasColumn('users', 'customer_type_id') ? 'customer_type_id' : null,
            Schema::hasColumn('users', 'sales_person') ? 'sales_person' : null,
            Schema::hasColumn('users', 'can_order') ? 'can_order' : null,
            Schema::hasColumn('users', 'can_view_price') ? 'can_view_price' : null,
            Schema::hasColumn('users', 'can_view_reward') ? 'can_view_reward' : null,
        ]));

        if ($columns !== []) {
            Schema::table('users', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }

    public function down(): void
    {
        //
    }
};
