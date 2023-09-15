<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ventas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha',
        'fecha_pago',
        'forma_pago',
        'cantidad',
        'total',
        'pagado',
    ];

    public function detalles(): HasMany
    {
        return $this->hasMany(VentaDetalle::class, 'id_venta', 'id');
    }
}
