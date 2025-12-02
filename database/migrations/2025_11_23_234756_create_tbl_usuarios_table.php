<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblUsuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('documento', 50)->unique();
            $table->string('correo', 150)->unique();
            $table->string('contrasena', 255);
            $table->string('telefono', 50)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('rol', 50)->default('cliente');
            $table->string('estado', 20)->default('activo');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->date('fecha_nacimiento')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblUsuarios');
    }
};
