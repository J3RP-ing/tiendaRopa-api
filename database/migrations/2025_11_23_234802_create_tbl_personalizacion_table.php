<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblPersonalizacion', function (Blueprint $table) {
            $table->id('id_personalizacion');
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_usuario');
            $table->string('tipo_personalizacion', 100);
            $table->string('talla', 50)->nullable();
            $table->decimal('precio_extra', 10, 2)->default(0);
            $table->text('diseno')->nullable();
            $table->softDeletes();

            $table->foreign('id_producto')->references('id_producto')->on('tblProductos')->onDelete('restrict');

            $table->foreign('id_usuario')->references('id_usuario')->on('tblUsuarios')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblPersonalizacion');
    }
};
