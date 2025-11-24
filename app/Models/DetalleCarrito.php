<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleCarrito extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tblDetalleCarrito';
    protected $primaryKey = 'id_detalle';
    public $timestamps = false;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id_carrito',
        'id_producto',
        'id_personalizacion',
        'cantidad',
        'subtotal',
    ];

    // Relación con carrito
    public function carrito()
    {
        return $this->belongsTo(Carrito::class, 'id_carrito', 'id_carrito');
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
