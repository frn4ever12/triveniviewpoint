<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'is_non_chargeable')) {
                $table->boolean('is_non_chargeable')->default(false)->after('change_amount');
            }
        });

        // Skip ENUM modifications for SQLite (not supported)
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE invoices MODIFY COLUMN payment_status ENUM('pending', 'partial', 'paid', 'refunded', 'failed', 'non_chargeable', 'cancelled') DEFAULT 'pending'");
            DB::statement("ALTER TABLE invoices MODIFY COLUMN payment_method ENUM('cash', 'card', 'digital_wallet', 'bank_transfer', 'credit_bill') DEFAULT NULL");
        }
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('is_non_chargeable');
        });

        DB::statement("ALTER TABLE invoices MODIFY COLUMN payment_status ENUM('pending', 'partial', 'paid', 'refunded', 'failed') DEFAULT 'pending'");
        DB::statement("ALTER TABLE invoices MODIFY COLUMN payment_method ENUM('cash', 'card', 'digital_wallet', 'bank_transfer') DEFAULT NULL");
    }
};
