<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            $table->string('invoice_number')->unique()->comment('e.g. INV-20250916-001');

            // Customer snapshot
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('delivery_address')->nullable();


            // Financial snapshot
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('vat_percent', 5, 2)->default(0);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('service_charge',10,2)->nullable()->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);

            // Payment
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'refunded', 'failed'])->default('pending');
            $table->enum('payment_method', ['cash', 'card', 'digital_wallet', 'bank_transfer'])->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('tender_amount', 10, 2)->nullable();
            $table->decimal('change_amount', 10, 2)->nullable();


            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
