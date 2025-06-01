<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'venta_mh_departamento';
    protected $primaryKey = 'id_departamento';
    public $timestamps = false;

    protected $fillable = [
        'departamento',
        'cod_mh_departamento',
        'estado'
    ];

    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'cod_mh_departamento', 'cod_mh_departamento');
    }
}