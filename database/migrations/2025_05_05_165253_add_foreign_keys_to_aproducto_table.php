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
        Schema::table('aproducto', function (Blueprint $table) {
            $table->foreign(['Producto'], 'aproducto_ibfk_1')->references(['IDProducto'])->on('inventario')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['Alquiler'], 'aproducto_ibfk_2')->references(['ID'])->on('alquiler')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aproducto', function (Blueprint $table) {
            $table->dropForeign('aproducto_ibfk_1');
            $table->dropForeign('aproducto_ibfk_2');
        });
    }
};
