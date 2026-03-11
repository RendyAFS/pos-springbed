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
        Schema::table('transaction_item_costs', function (Blueprint $table) {
            $table->renameColumn('transaction_items_id', 'transaction_item_id');
            $table->renameColumn('purchase_order_items_id', 'purchase_order_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_item_costs', function (Blueprint $table) {
            $table->renameColumn('transaction_item_id', 'transaction_item_id');
            $table->renameColumn('purchase_order_item_id', 'purchase_order_item_id');
        });
    }
};
