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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('store_setting_id')->nullable()->after('id')->constrained('store_settings');
        });

        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->foreignId('store_setting_id')->nullable()->after('id')->constrained('store_settings');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->foreignId('store_setting_id')->nullable()->after('id')->constrained('store_settings');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('store_setting_id')->nullable()->after('id')->constrained('store_settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['store_setting_id']);
            $table->dropColumn('store_setting_id');
        });

        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->dropForeign(['store_setting_id']);
            $table->dropColumn('store_setting_id');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['store_setting_id']);
            $table->dropColumn('store_setting_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['store_setting_id']);
            $table->dropColumn('store_setting_id');
        });
    }
};
