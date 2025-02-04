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
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con usuarios
            $table->foreignId('evento_id')->constrained()->onDelete('cascade'); // Relación con eventos
            $table->enum('tipo_inscripcion', ['presencial', 'virtual', 'gratuita']);
            $table->timestamps();

            // Restricciones únicas: un usuario no puede inscribirse en el mismo evento más de una vez
            $table->unique(['user_id', 'evento_id']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {Schema::table('inscripciones', function (Blueprint $table) {
        $table->dropForeign(['user_id']); // Elimina la clave foránea
    });

    Schema::dropIfExists('inscripciones');
        //
    }
};
