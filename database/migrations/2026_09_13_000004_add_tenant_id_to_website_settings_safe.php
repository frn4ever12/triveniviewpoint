<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only add tenant_id if it doesn't exist and column is not already there
        if (Schema::hasTable('website_settings') && !Schema::hasColumn('website_settings', 'tenant_id')) {
            Schema::table('website_settings', function (Blueprint $table) {
                $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->onDelete('cascade');
                $table->index('tenant_id');
            });
            
            // Set existing records to null (they will be treated as default/global settings)
            DB::table('website_settings')->update(['tenant_id' => null]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('website_settings') && Schema::hasColumn('website_settings', 'tenant_id')) {
            Schema::table('website_settings', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
                $table->dropIndex(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
    }
};
