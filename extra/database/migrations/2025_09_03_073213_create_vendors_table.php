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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('company_name')->nullable()->comment('Company/Business name');
            $table->string('contact_person')->nullable()->comment('Primary contact person');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('alternate_phone')->nullable()->comment('Secondary phone number');
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable()->default('Nepal');
            
            // Business Information
            $table->string('pan_no')->nullable()->comment('PAN Number');
            $table->string('website')->nullable()->comment('Company website');
            
        
            
            // Vendor Rating & Notes
            $table->text('notes')->nullable()->comment('Additional notes about vendor');
            
            // Status & Tracking
            $table->string('status')->default('active');
           $table->foreignId('entry_user_id')->nullable()->constrained('users')->nullOnDelete()->comment('User who created this vendor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
