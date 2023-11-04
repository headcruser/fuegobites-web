<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Cliente extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'telefono_fijo',
        'celular',
        'direccion',
        'ciudad',
        'estado',
        'observaciones',
    ];

    protected $dates = ['created_at'];

    protected $appends = [];

    protected function observaciones(): Attribute
    {
        return new Attribute(
            set: function ($value, $attributes) {

                if (empty($value)) {
                    return $attributes['observaciones'] = '';
                }

                return $attributes['observaciones'] = $value;
            }
        );
    }

    protected function status(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                return empty($this->deletet_at) ? 'Activo' : 'Desactivado';
            }
        );
    }

    protected function statusClass(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                return empty($this->deletet_at) ? 'success' : 'danger';
            }
        );
    }
}
