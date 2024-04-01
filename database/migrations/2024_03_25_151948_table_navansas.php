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
        Schema::create('navansas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained();
            $table->foreignId('graha_id')->constrained();
            $table->bigInteger('parent_id')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navansas');
    }
};
