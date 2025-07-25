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
        Schema::table('conjunto_configs', function (Blueprint $table) {
            $table->dropColumn('base_administration_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conjunto_configs', function (Blueprint $table) {
            $table->decimal('base_administration_fee', 10, 2)->after('apartments_per_floor');
        });
    }
};
