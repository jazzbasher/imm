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
        Schema::create('salesfore', function (Blueprint $table) {
            $table->string('item_id')->primary();
            $table->string('item_desc')->nullable();
            $table->integer('customer_id');
            $table->string('customer_name')->nullable();
            $table->integer('times_sold_cnt')->nullable();
            $table->integer('total_qty_sold')->nullable();
            $table->string('uom')->nullable();
            $table->decimal('unit_price',10,2)->nullable();
            $table->date('date_last_sold')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salesfore');
    }
};
