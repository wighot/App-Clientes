<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'venta_mh_municipio';
    protected $primaryKey = 'id_municipio';
    public $timestamps = false;

    protected $fillable = [
        'municipio',
        'cod_mh_municipio',
        'cod_mh_departamento'
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'cod_mh_departamento', 'cod_mh_departamento');
    }
}