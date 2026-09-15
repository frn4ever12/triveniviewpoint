<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('menu_item_id')->constrained('menu_items')->onDelete('cascade');

            // KOT integration
            $table->foreignId('kot_id')->nullable()->constrained('kots')->onDelete('set null');
            $table->boolean('is_kitchen_item')->default(true);
            $table->timestamp('kot_printed_at')->nullable();

            // Quantity & Pricing
            $table->string('size')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);

            // Modifiers (JSON based)
            $table->json('modifiers')->nullable();

            // Status Tracking
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'status']);
            $table->index(['menu_item_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
