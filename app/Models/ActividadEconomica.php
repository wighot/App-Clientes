<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActividadEconomica extends Model
{
    protected $table = 'venta_mh_actividad_economica';
    protected $primaryKey = 'id_actividad_economica';
    public $timestamps = false;

    protected $fillable = [
        'actividad_economica',
        'cod_actividad_economica',
        'estado'
    ];
}