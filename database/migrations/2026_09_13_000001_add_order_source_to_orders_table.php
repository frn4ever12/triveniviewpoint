<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_source', ['pos', 'waiter', 'qr'])->default('waiter')->after('order_type');
            $table->string('customer_session_token')->nullable()->after('customer_phone')->comment('Token for QR order tracking');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_source', 'customer_session_token']);
        });
    }
};
