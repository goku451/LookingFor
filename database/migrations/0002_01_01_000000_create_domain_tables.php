<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Objetos perdidos ──
        Schema::create('objetos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->enum('tipo', ['Personal', 'Material de Estudio', 'Tecnológico']);
            $table->date('fecha');
            $table->time('hora');
            $table->string('lugar', 250);
            $table->text('descripcion');
            $table->string('imagen', 255)->nullable();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('tipo');
            $table->index('fecha');
        });

        // ── Reportes de objetos encontrados ──
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_reportante', 200);
            $table->string('codigo_reportante', 20)->nullable();
            $table->string('nivel', 100)->nullable();
            $table->string('grado', 100)->nullable();
            $table->string('seccion', 10)->nullable();
            $table->string('correo', 100)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->string('nombre_objeto', 100);
            $table->enum('tipo', ['Personal', 'Material de Estudio', 'Tecnológico']);
            $table->date('fecha');
            $table->time('hora');
            $table->string('lugar', 250);
            $table->text('descripcion');
            $table->string('imagen', 255)->nullable();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('tipo');
            $table->index('fecha');
        });

        // ── Publicaciones / comentarios ──
        Schema::create('publicaciones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->text('comentarios');
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publicaciones');
        Schema::dropIfExists('reportes');
        Schema::dropIfExists('objetos');
    }
};
