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
        Schema::table('adtrustee_map', function (Blueprint $table) {
            $table->boolean('is_isc')->default(true);
            $table->boolean('is_sp')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adtrustee_map', function (Blueprint $table) {
            //
        });
    }
};
