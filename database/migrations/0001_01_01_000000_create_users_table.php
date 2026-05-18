<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Roles ──
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->string('slug', 50)->unique();
            $table->timestamps();
        });

        // ── Users (unifica usuario + profesor + administrador) ──
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->string('codigo', 20)->nullable();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('telefono', 15)->nullable();
            $table->string('nivel', 100)->nullable();
            $table->string('grado', 100)->nullable();
            $table->string('seccion', 10)->nullable();
            $table->string('foto', 255)->nullable(); // ruta al archivo, NO blob
            $table->foreignId('role_id')
                  ->constrained('roles')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('role_id');
        });

        // ── Sessions ──
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
