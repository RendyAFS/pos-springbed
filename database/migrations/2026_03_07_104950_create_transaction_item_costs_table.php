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
        Schema::create('transaction_item_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_items_id')->nullable()->constrained('transaction_items');
            $table->foreignId('purchase_order_items_id')->nullable()->constrained('purchase_order_items');
            $table->integer('qty_taken')->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_item_costs');
    }
};
