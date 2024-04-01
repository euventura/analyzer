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
        Schema::create('bhavas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('varga_id')->constrained();
            $table->tinyInteger('house');
            $table->decimal('cusp');
            $table->decimal('cusp_nakshatra')->nullable();
            $table->decimal('start')->nullable();
            $table->decimal('end')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bhavas');
    }
};
