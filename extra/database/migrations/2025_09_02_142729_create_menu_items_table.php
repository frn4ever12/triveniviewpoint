<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->text('ingredients')->nullable();

            // Pricing
            $table->decimal('price', 10, 2)->nullable()->comment('Selling price');
            $table->enum('discount_type', ['amount', 'percentage'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->decimal('final_price', 10, 2)->nullable();

            // Restaurant-specific
            $table->string('preparation_time')->nullable();
            $table->boolean('is_vegetarian')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
