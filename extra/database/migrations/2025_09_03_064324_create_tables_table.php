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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('capacity')->nullable()->default(0)->comment('Maximum seating capacity');
            $table->string('table_type')->default('regular')->comment('regular, vip, outdoor, private');
            $table->string('location')->nullable()->comment('Table location in restaurant');
            $table->string('floor')->nullable()->comment('Floor number');
            $table->string('section')->nullable()->comment('Restaurant section');
            
            // Table Features
            $table->boolean('has_air_conditioning')->default(false)->comment('Has AC');
            $table->boolean('has_tv')->default(false)->comment('Has TV');
            $table->boolean('has_wifi')->default(false)->comment('Has WiFi');
            $table->boolean('is_smoking_allowed')->default(false)->comment('Smoking allowed');
            $table->boolean('is_wheelchair_accessible')->default(false)->comment('Wheelchair accessible');
            
           
            // Table Management
            $table->string('status')->default('available')->comment('available, occupied');
            $table->timestamp('reserved_until')->nullable()->comment('Reservation expiry time');
            
          
            // Notes & Maintenance
            $table->text('notes')->nullable()->comment('Table notes');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
