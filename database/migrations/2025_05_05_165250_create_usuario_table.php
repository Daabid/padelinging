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
        Schema::create('usuario', function (Blueprint $table) {
            $table->string('DNI', 9)->primary();
            $table->string('Nombre', 15);
            $table->string('Apellido', 20);
            $table->string('Correo', 25);
            $table->date('FechaNacimiento');
            $table->string('Contrasena');
            $table->string('Rol', 10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
