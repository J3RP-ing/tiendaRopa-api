<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tblPedidos';
    protected $primaryKey = 'id_pedido';
    public $timestamps = false;

    protected $dates = ['deleted_at', 'fecha_pedido'];

    protected $fillable = [
        'id_usuario',
        'fecha_pedido',
        'total',
        'estado',
        'direccion_envio',
        'metodo_pago',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
