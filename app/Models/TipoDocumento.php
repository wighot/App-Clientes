<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = 'venta_mh_tipo_documento';
    protected $primaryKey = 'id_tipo_documento';
    public $timestamps = false;

    protected $fillable = [
        'tipo_documento',
        'cod_tipo_documento',
        'estado'
    ];
}