<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'group')) {
                $table->string('group')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'default_price')) {
                $table->decimal('default_price', 10, 2)->default(0)->after('group');
            }
            if (!Schema::hasColumn('products', 'opening_stock_quantity')) {
                $table->decimal('opening_stock_quantity', 10, 2)->default(0)->after('default_price');
            }
            if (!Schema::hasColumn('products', 'opening_stock_rate')) {
                $table->decimal('opening_stock_rate', 10, 2)->default(0)->after('opening_stock_quantity');
            }
            if (!Schema::hasColumn('products', 'multiple_unit')) {
                $table->boolean('multiple_unit')->default(false)->after('opening_stock_rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['group', 'default_price', 'opening_stock_quantity', 'opening_stock_rate', 'multiple_unit']);
        });
    }
};
