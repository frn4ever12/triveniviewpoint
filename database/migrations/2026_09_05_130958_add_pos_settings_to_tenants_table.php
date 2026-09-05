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
        Schema::table('tenants', function (Blueprint $table) {
            $table->decimal('vat_percent', 5, 2)->default(13)->after('logo');
            $table->decimal('service_charge_percent', 5, 2)->default(10)->after('vat_percent');
            $table->string('default_payment_method', 20)->default('cash')->after('service_charge_percent');
            $table->boolean('auto_print_receipt')->default(true)->after('default_payment_method');
            $table->string('receipt_footer', 200)->nullable()->after('auto_print_receipt');
            $table->boolean('enable_kot')->default(true)->after('receipt_footer');
            $table->boolean('enable_table_reservation')->default(true)->after('enable_kot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'vat_percent',
                'service_charge_percent',
                'default_payment_method',
                'auto_print_receipt',
                'receipt_footer',
                'enable_kot',
                'enable_table_reservation'
            ]);
        });
    }
};
