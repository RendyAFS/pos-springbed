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
        Schema::table('product_sizes', function (Blueprint $table) {
            if (Schema::hasColumn('product_sizes', 'value')) {
                $table->dropColumn('value');
            }
        });

        Schema::table('product_types', function (Blueprint $table) {
            if (Schema::hasColumn('product_types', 'value')) {
                $table->dropColumn('value');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_sizes', function (Blueprint $table) {
            if (! Schema::hasColumn('product_sizes', 'value')) {
                $table->string('value')->nullable()->unique()->after('name');
            }
        });

        Schema::table('product_types', function (Blueprint $table) {
            if (! Schema::hasColumn('product_types', 'value')) {
                $table->string('value')->nullable()->unique()->after('name');
            }
        });
    }
};
