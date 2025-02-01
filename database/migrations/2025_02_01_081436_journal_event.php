<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->enum('tipo', ['conferencia', 'taller']);
            $table->date('fecha'); // Fecha del evento
            $table->time('hora_inicio'); // Hora de inicio
            $table->time('hora_fin'); // Hora de finalización
            $table->foreignId('ponente_id')->constrained()->onDelete('cascade'); // Relación con ponentes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
