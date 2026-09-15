<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();

            // Foreign Keys
            $table->foreignId('table_id')->nullable()->constrained('tables')->onDelete('set null');
            $table->foreignId('waiter_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('entry_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('no_of_guests')->nullable();

            // Order-level payment tracking
            $table->enum('payment_status', ['pending','paid', 'failed', 'refunded', 'cancelled'])->default('pending');
          
            // Order Type & KOT integration
            $table->enum('order_type', ['dine_in', 'pickup', 'delivery'])->default('dine_in');
            $table->string('kot_group_id')->nullable()->comment('Batch ID for grouping KOTs');
            $table->timestamp('kot_sent_at')->nullable();

            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'served', 'cancelled', 'completed'])->default('pending');
            $table->enum('delivery_status', ['pending', 'on the way', 'delivered','cancelled'])->default('pending');
            $table->text('notes')->nullable();


            $table->string('customer_name')->nullable();
            $table->string('customer_phone', 20)->nullable();
            $table->text('delivery_address')->nullable();
            
            $table->timestamps();

            // Indexes
            $table->index(['status', 'created_at']);
            $table->index(['payment_status', 'created_at']);
            $table->index(['table_id', 'status']);
            $table->index('waiter_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
