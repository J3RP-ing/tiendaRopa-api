<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblPagos', function (Blueprint $table) {
            $table->id('id_pago');
            $table->unsignedBigInteger('id_pedido')->unique()->nullable();
            $table->timestamp('fecha_pago')->useCurrent();
            $table->string('metodo', 100);
            $table->decimal('monto', 10, 2);
            $table->string('estado', 50)->default('completado');
            $table->string('referencia_transaccion', 255)->nullable();
            $table->softDeletes();

            $table->foreign('id_pedido')->references('id_pedido')->on('tblPedidos')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblPagos');
    }
};
