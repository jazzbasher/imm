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
        Schema::create('freightlog', function (Blueprint $table) {
            $table->id();
            $table->datetime('date');
            $table->integer('customer_id')->nullable();
            $table->string('buyer')->nullable();
            $table->string('salesrep')->nullable();
            $table->string('po')->nullable();
            $table->decimal('amount', 8, 2)->nullable();
            $table->string('order_no')->nullable();
            $table->string('notes', 500)->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freightlog');
    }
};
