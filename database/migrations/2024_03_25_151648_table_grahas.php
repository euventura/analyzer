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
            $table->foreignId('varga_id')->constrained();
            $table->foreignId('bhava_id')->constrained();
            $table->string('name');
            $table->string('nakshatra')->nullable();
            $table->json('lordship')->nullable();
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
