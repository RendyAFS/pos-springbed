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
        Schema::create('referals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->cascadeOnDelete();
            $table->string('name_customer')->nullable();
            $table->string('referal_code')->nullable()->unique();
            $table->integer('discount_amount')->nullable();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('is_referal')->default(false)->after('transaction_date');
            $table->foreignId('referal_customer_id')->nullable()->after('is_referal')->constrained('customers')->cascadeOnDelete();
            $table->integer('nominal_referal')->nullable()->after('referal_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referals');
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('is_referal');
            $table->dropForeign(['referal_customer_id']);
            $table->dropColumn('referal_customer_id');
            $table->dropColumn('nominal_referal');
        });
    }
};
