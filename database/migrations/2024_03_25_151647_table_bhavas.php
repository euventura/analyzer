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
            $table->tinyInteger('division');
            $table->tinyInteger('house');
            $table->tinyInteger('graha');
            $table->decimal('cusp');
            $table->string('cusp_nakshatra')->nullable();
            $table->string('cusp_nakshatra_lord')->nullable();
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
