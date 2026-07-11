<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('erp_items', 'website_image_url')) {
            Schema::table('erp_items', function (Blueprint $table) {
                $table->dropColumn('website_image_url');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('erp_items', 'website_image_url')) {
            Schema::table('erp_items', function (Blueprint $table) {
                $table->string('website_image_url')->nullable()->after('image_url');
            });
        }
    }
};
