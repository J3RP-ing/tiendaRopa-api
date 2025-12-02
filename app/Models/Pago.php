<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pago extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tblPagos';
    protected $primaryKey = 'id_pago';
    public $timestamps = false;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id_pedido',
        'fecha_pago',
        'metodo',
        'monto',
        'estado',
        'referencia_transaccion',
    ];

    // Relación: un pago pertenece a un pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }
}

