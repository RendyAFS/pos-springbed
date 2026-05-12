<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('products')->update([
            'store_setting_id' => null,
        ]);
        
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['store_setting_id']);
            $table->dropColumn('store_setting_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('store_setting_id')->nullable()->after('id')->constrained('store_settings')->nullOnDelete();
        });
    }
};
