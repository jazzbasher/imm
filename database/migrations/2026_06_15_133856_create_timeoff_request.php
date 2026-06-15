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
        Schema::create('timeoff_request', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('manager_id');
            $table->integer('type'); // e.g., vacation, sick
            $table->string('title');
            $table->datetime('start');
            $table->datetime('end');
            $table->integer('status')->default(0); // pending, approved, rejected
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timeoff_request');
    }
};
