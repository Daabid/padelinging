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
        Schema::table('incidencia', function (Blueprint $table) {
            $table->foreign(['Reserva'], 'incidencia_ibfk_1')->references(['ID'])->on('reserva')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidencia', function (Blueprint $table) {
            $table->dropForeign('incidencia_ibfk_1');
        });
    }
};
