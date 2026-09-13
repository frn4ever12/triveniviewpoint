<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->decimal('cost_price', 10, 2)->nullable()->after('price');
            $table->decimal('vat_percent', 5, 2)->nullable()->after('cost_price');
            $table->string('spice_level')->nullable()->after('preparation_time');
            $table->boolean('is_gluten_free')->default(false)->after('is_vegetarian');
            $table->boolean('is_halal')->default(false)->after('is_gluten_free');
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn(['cost_price', 'vat_percent', 'spice_level', 'is_gluten_free', 'is_halal']);
        });
    }
};
