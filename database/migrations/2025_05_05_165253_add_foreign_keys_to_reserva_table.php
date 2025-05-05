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
        Schema::table('reserva', function (Blueprint $table) {
            $table->foreign(['Usuario'], 'reserva_ibfk_1')->references(['DNI'])->on('usuario')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['Pista'], 'reserva_ibfk_2')->references(['IDPista'])->on('pista')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['Alquiler'], 'reserva_ibfk_3')->references(['ID'])->on('alquiler')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reserva', function (Blueprint $table) {
            $table->dropForeign('reserva_ibfk_1');
            $table->dropForeign('reserva_ibfk_2');
            $table->dropForeign('reserva_ibfk_3');
        });
    }
};
