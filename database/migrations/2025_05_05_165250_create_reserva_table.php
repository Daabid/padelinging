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
        Schema::create('reserva', function (Blueprint $table) {
            $table->integer('ID', true);
            $table->string('Usuario', 9)->index('usuario');
            $table->string('Pista', 20)->index('pista');
            $table->string('Alquiler', 20)->nullable()->index('alquiler');
            $table->dateTime('FInicio');
            $table->dateTime('FFinal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva');
    }
};
