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
        Schema::create('pista', function (Blueprint $table) {
            $table->string('IDPista', 20)->primary();
            $table->string('Tipo', 15);
            $table->decimal('Superficie');
            $table->string('Estado', 15);
            $table->decimal('Precio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pista');
    }
};
