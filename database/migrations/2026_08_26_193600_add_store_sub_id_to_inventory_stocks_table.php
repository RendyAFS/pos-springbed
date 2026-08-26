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
        Schema::table('inventory_stocks', function (Blueprint $table) {
            $table->foreignId('store_sub_id')->nullable()->after('store_setting_id')->constrained('store_subs')->nullOnDelete();
        });

        // Set default floor for existing stock records
        $defaultSubs = DB::table('store_subs')->where('type', 'Floor')->pluck('id', 'store_id');
        foreach ($defaultSubs as $storeId => $subId) {
            DB::table('inventory_stocks')
                ->where('store_setting_id', $storeId)
                ->whereNull('store_sub_id')
                ->update(['store_sub_id' => $subId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_stocks', function (Blueprint $table) {
            $table->dropForeign(['store_sub_id']);
            $table->dropColumn('store_sub_id');
        });
    }
};
