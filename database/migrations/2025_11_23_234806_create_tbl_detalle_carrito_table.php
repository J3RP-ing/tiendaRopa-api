<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblDetalleCarrito', function (Blueprint $table) {
            $table->id('id_detalle');
            $table->unsignedBigInteger('id_carrito');
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_personalizacion')->nullable();
            $table->integer('cantidad')->default(1);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->softDeletes();

            $table->foreign('id_carrito')->references('id_carrito')->on('tblCarrito')->onDelete('cascade');

            $table->foreign('id_producto')->references('id_producto')->on('tblProductos')->onDelete('restrict');

            $table->foreign('id_personalizacion')->references('id_personalizacion')->on('tblPersonalizacion')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblDetalleCarrito');
    }
};
