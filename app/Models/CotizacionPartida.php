<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotizacionPartida extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones_partidas';

    protected $fillable = [
        'posicion',
        'id_cotizacion',
        'id_producto',
        'concepto',
        'descripcion',
        'cantidad',
        'costo',
        'precio',
        'total',
        'moneda',
        'precio_convertido',
        'descuento_convertido',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'cantidad' => 'integer',
    ];

    protected $appends = [];

    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'id_cotizacion', 'id')->withDefault();
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id')->withDefault();
    }
}
