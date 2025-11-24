<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblCarrito', function (Blueprint $table) {
            $table->id('id_carrito');
            $table->unsignedBigInteger('id_usuario')->unique();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->boolean('estado')->default(1);
            $table->softDeletes();

            $table->foreign('id_usuario')->references('id_usuario')->on('tblUsuarios')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblCarrito');
    }
};
