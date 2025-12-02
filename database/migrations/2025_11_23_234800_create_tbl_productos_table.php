<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblProductos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->unsignedBigInteger('id_categoria');
            $table->string('nombre_producto', 150);
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->integer('stock')->default(0);
            $table->string('talla', 50)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('imagen_url', 255)->nullable();
            $table->string('estado', 20)->default('activo');
            $table->softDeletes();

            $table->foreign('id_categoria')->references('id_categoria')->on('tblCategorias')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblProductos');
    }
};
