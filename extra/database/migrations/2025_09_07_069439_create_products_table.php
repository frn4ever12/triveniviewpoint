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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->nullable()->unique();
            $table->text('description')->nullable();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->foreignId('purchase_unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->foreignId('consumption_unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->decimal('conversion_ratio', 10, 2)->nullable()->default(1);
            $table->string('group')->nullable();
            $table->string('item_type')->nullable()->default('ingredient');
            $table->decimal('default_price', 10, 2)->nullable()->default(0);
            $table->decimal('purchase_cost', 10, 2)->nullable()->default(0);
            $table->decimal('average_cost', 10, 2)->nullable()->default(0);
            $table->decimal('opening_stock_quantity', 10, 2)->nullable()->default(0);
            $table->decimal('opening_stock_rate', 10, 2)->nullable()->default(0);
            $table->boolean('multiple_unit')->default(false);
            $table->decimal('minimum_stock', 10, 2)->nullable()->default(0);
            $table->decimal('reorder_level', 10, 2)->nullable()->default(0);
            $table->decimal('maximum_stock', 10, 2)->nullable()->default(0);
            $table->foreignId('supplier_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->string('storage_location')->nullable();
            $table->string('status')->default('active');
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
