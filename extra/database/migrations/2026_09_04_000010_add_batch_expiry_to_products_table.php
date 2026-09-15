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
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->unique()->after('name');
            }
            if (!Schema::hasColumn('products', 'item_type')) {
                $table->string('item_type')->nullable()->after('group');
            }
            if (!Schema::hasColumn('products', 'purchase_unit_id')) {
                $table->foreignId('purchase_unit_id')->nullable()->constrained('units')->onDelete('set null')->after('unit_id');
            }
            if (!Schema::hasColumn('products', 'consumption_unit_id')) {
                $table->foreignId('consumption_unit_id')->nullable()->constrained('units')->onDelete('set null')->after('purchase_unit_id');
            }
            if (!Schema::hasColumn('products', 'conversion_ratio')) {
                $table->decimal('conversion_ratio', 10, 2)->default(1)->after('consumption_unit_id');
            }
            if (!Schema::hasColumn('products', 'minimum_stock')) {
                $table->decimal('minimum_stock', 10, 2)->default(0)->after('conversion_ratio');
            }
            if (!Schema::hasColumn('products', 'reorder_level')) {
                $table->decimal('reorder_level', 10, 2)->default(0)->after('minimum_stock');
            }
            if (!Schema::hasColumn('products', 'maximum_stock')) {
                $table->decimal('maximum_stock', 10, 2)->nullable()->after('reorder_level');
            }
            if (!Schema::hasColumn('products', 'purchase_cost')) {
                $table->decimal('purchase_cost', 10, 2)->default(0)->after('default_price');
            }
            if (!Schema::hasColumn('products', 'average_cost')) {
                $table->decimal('average_cost', 10, 2)->default(0)->after('purchase_cost');
            }
            if (!Schema::hasColumn('products', 'supplier_id')) {
                $table->foreignId('supplier_id')->nullable()->constrained('vendors')->onDelete('set null')->after('average_cost');
            }
            if (!Schema::hasColumn('products', 'storage_location')) {
                $table->string('storage_location')->nullable()->after('supplier_id');
            }
            if (!Schema::hasColumn('products', 'status')) {
                $table->string('status')->default('active')->after('storage_location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'sku', 'item_type', 'purchase_unit_id', 'consumption_unit_id',
                'conversion_ratio', 'minimum_stock', 'reorder_level', 'maximum_stock',
                'purchase_cost', 'average_cost', 'supplier_id', 'storage_location', 'status'
            ]);
        });
    }
};
