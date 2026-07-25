<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('city_code')->nullable()->after('address');
            $table->string('city_name')->nullable()->after('city_code');
            $table->string('district_code')->nullable()->after('city_name');
            $table->string('district_name')->nullable()->after('district_code');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'city_code',
                'city_name',
                'district_code',
                'district_name',
            ]);
        });
    }
};
