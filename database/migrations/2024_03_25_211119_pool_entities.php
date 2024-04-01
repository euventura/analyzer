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
        Schema::create('pool_entities', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->longText('page_content')->nullable();
            $table->json('info')->nullable();
            $table->enum('status',['pending', 'content', 'info', 'done'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pool_entities');
    }
};
