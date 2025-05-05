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
        Schema::create('aproducto', function (Blueprint $table) {
            $table->string('Producto', 20);
            $table->string('Alquiler', 20)->index('alquiler');

            $table->primary(['Producto', 'Alquiler']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aproducto');
    }
};
