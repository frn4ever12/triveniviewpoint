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
        Schema::create('digital_menu_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('primary_color')->default('#dc2626');
            $table->string('secondary_color')->default('#1a1a2e');
            $table->string('accent_color')->default('#f59e0b');
            $table->string('background_color')->default('#f8f9fa');
            $table->string('text_color')->default('#1f2937');
            $table->string('font_family')->default('Inter');
            $table->string('card_style')->default('modern');
            $table->boolean('show_categories')->default(true);
            $table->boolean('show_search')->default(true);
            $table->boolean('show_prices')->default(true);
            $table->boolean('show_images')->default(true);
            $table->string('layout')->default('grid');
            $table->json('custom_css')->nullable();
            $table->timestamps();
            
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_menu_designs');
    }
};
