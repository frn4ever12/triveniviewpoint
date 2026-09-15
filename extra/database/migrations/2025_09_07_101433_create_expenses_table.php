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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number')->unique()->comment('Unique expense number');
            $table->foreignId('label_id')->nullable()->constrained('labels')->nullOnDelete()->comment('Expense category/label');
            $table->foreignId('employee_id')->nullable()->constrained('users')->nullOnDelete()->comment('Employee who incurred expense');

            // Expense Information
            $table->string('title')->comment('Expense title/description');
            $table->text('description')->nullable()->comment('Detailed description');
            $table->date('expense_date')->nullable()->comment('Date expense');
            $table->string('expense_date_bs',16)->nullable()->comment('Date expense BS');
            $table->date('payment_date')->nullable()->comment('Date expense was paid');
            $table->string('payment_date_bs',16)->nullable()->comment('Date expense was paid BS');

            // Financial Information
            $table->decimal('amount', 12, 2)->nullable()->comment('Expense amount');
            $table->decimal('tax_percent', 5, 2)->default(0)->comment('Tax percentage');
            $table->decimal('tax_amount', 10, 2)->default(0)->comment('Tax amount');
            $table->decimal('total_amount', 10, 2)->default(0)->comment('Total amount');


            // Payment Information
            $table->string('payment_method')->nullable()->comment('cash, bank_transfer, card, check, digital_wallet');
            $table->string('payment_reference')->nullable()->comment('Payment reference number');

            // Approval & Authorization
            $table->string('status')->default('pending')->comment('pending, approved, rejected, paid, cancelled');

            // Notes & Communication
            $table->text('remarks')->nullable()->comment('General remarks');

            // Tracking & Analytics
            $table->foreignId('entry_user_id')->nullable()->constrained('users')->nullOnDelete()->comment('User who created');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
