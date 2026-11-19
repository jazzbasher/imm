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
        Schema::create('pod_documents', function (Blueprint $table) {
            $table->id();
            $table->string('filename')->unique();
            $table->string('file_path');
            $table->unsignedBigInteger('file_modified_at')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();

            // MySQL FULLTEXT index for instant content & filename searching
            $table->fullText(['filename', 'content']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pod_documents');
    }
};
