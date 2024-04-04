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
        Schema::create('grahas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained();
            $table->tinyInteger('bhava');
            $table->string('graha');
            $table->string('nakshatra')->nullable();
            $table->string('nakshatra_lord')->nullable();
            $table->json('lordship_of')->nullable();
            $table->decimal('degree', 16, 14);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grahas');
    }
};
