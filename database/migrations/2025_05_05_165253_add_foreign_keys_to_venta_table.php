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
        Schema::table('venta', function (Blueprint $table) {
            $table->foreign(['Producto'], 'venta_ibfk_1')->references(['IDProducto'])->on('inventario')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['Carrito'], 'venta_ibfk_2')->references(['ID'])->on('carrito')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venta', function (Blueprint $table) {
            $table->dropForeign('venta_ibfk_1');
            $table->dropForeign('venta_ibfk_2');
        });
    }
};
