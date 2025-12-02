<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblDetallePedido', function (Blueprint $table) {
            $table->id('id_detalle_pedido');
            $table->unsignedBigInteger('id_pedido');
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_personalizacion')->nullable();
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->softDeletes();

            $table->foreign('id_pedido')->references('id_pedido')->on('tblPedidos')->onDelete('cascade');

            $table->foreign('id_producto')->references('id_producto')->on('tblProductos')->onDelete('restrict');

            $table->foreign('id_personalizacion')->references('id_personalizacion')->on('tblPersonalizacion')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblDetallePedido');
    }
};
