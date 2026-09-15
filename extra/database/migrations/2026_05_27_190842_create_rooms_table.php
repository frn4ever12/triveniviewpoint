<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('room_number')->unique()->comment('Room number/identifier');
            $table->string('room_type')->default('standard')->comment('standard, deluxe, suite, penthouse, etc.');
            $table->string('floor')->nullable()->comment('Floor number');
            $table->decimal('price_per_night', 10, 2)->default(0)->comment('Base price per night');
            $table->integer('capacity')->default(2)->comment('Maximum guest capacity');
            $table->integer('bed_count')->default(1)->comment('Number of beds');
            $table->string('bed_type')->nullable()->comment('single, double, queen, king, twin, etc.');

            // Amenities
            $table->boolean('has_ac')->default(false);
            $table->boolean('has_tv')->default(false);
            $table->boolean('has_wifi')->default(false);
            $table->boolean('has_minibar')->default(false);
            $table->boolean('has_balcony')->default(false);
            $table->boolean('is_smoking_allowed')->default(false);
            $table->boolean('is_wheelchair_accessible')->default(false);

            // Status & Management
            $table->string('status')->default('available')->comment('available, occupied, maintenance, reserved');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
