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
        Schema::table('timeclock', function (Blueprint $table) {
            $table->timestamp('lunch_in')->nullable();
            $table->timestamp('lunch_out')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timeclock', function (Blueprint $table) {
            //
        });
    }
};
