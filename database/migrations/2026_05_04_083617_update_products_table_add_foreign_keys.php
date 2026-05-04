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
            // Add new columns for foreign keys
            $table->unsignedBigInteger('size_id')->nullable()->after('sku');
            $table->unsignedBigInteger('type_id')->nullable()->after('size_id');
            
            // Add foreign key constraints
            $table->foreign('size_id')->references('id')->on('product_sizes')->nullOnDelete();
            $table->foreign('type_id')->references('id')->on('product_types')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeignKey(['size_id']);
            $table->dropForeignKey(['type_id']);
            $table->dropColumn(['size_id', 'type_id']);
        });
    }
};
