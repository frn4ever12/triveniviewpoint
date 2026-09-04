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
        $tables = [
            'categories',
            'menu_items',
            'tables',
            'orders',
            'order_items',
            'kots',
            'products',
            'purchases',
            'purchase_items',
            'expenses',
            'suppliers',
            'units',
            'labels',
            'invoices',
            'rooms',
            'stock_usages',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'tenant_id')) {
                        $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->onDelete('cascade');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'categories',
            'menu_items',
            'tables',
            'orders',
            'order_items',
            'kots',
            'products',
            'purchases',
            'purchase_items',
            'expenses',
            'suppliers',
            'units',
            'labels',
            'invoices',
            'rooms',
            'stock_usages',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (Schema::hasColumn($tableName, 'tenant_id')) {
                        $table->dropForeign(['tenant_id']);
                        $table->dropColumn('tenant_id');
                    }
                });
            }
        }
    }
};
