<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoContribuyente extends Model
{
    protected $table = 'venta_mh_tipo_contribuyente';
    protected $primaryKey = 'id_tipo_contribuyente';
    public $timestamps = false;

    protected $fillable = [
        'tipo_contribuyente',
        'estado'
    ];
}