<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetallePedido extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tblDetallePedido';
    protected $primaryKey = 'id_detalle_pedido';
    public $timestamps = false;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id_pedido',
        'id_producto',
        'id_personalizacion',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    // Relación con pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    // Relación con producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    // Relación con personalización
    public function personalizacion()
    {
        return $this->belongsTo(Personalizacion::class, 'id_personalizacion', 'id_personalizacion');
    }
}

