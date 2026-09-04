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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('transaction_type'); // purchase, sale, adjustment, wastage, transfer, kitchen_consumption, opening_stock, etc.
            $table->string('reference_type')->nullable(); // purchase, order, adjustment, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('opening_balance', 10, 2)->default(0);
            $table->decimal('stock_in', 10, 2)->default(0);
            $table->decimal('stock_out', 10, 2)->default(0);
            $table->decimal('closing_balance', 10, 2)->default(0);
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->decimal('total_value', 10, 2)->default(0);
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('manufacturing_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'tenant_id']);
            $table->index(['transaction_type', 'reference_type', 'reference_id'], 'trx_ref_index');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
