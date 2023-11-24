<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cotizacion extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cotizaciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'folio',
        'fecha',

        'nombre',
        'id_cliente',
        'id_asesor',

        'saludo',
        'descripcion',
        'observaciones',
        'pie',

        'moneda',
        'tipo_cambio',
        'forma_pago',
        'metodo_pago',
        'total',

        'status'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id')->withDefault([
            'nombre'        => 'Sin Cliente',
        ]);
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'id_asesor', 'id')->withDefault([
            'name' => '',
        ]);
    }

    public function partidas()
    {
        return $this->hasMany(CotizacionPartida::class, 'id_cotizacion', 'id');
    }
}
