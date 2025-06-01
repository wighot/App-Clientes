<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'venta_mh_pais';
    protected $primaryKey = 'id_pais';
    
    protected $fillable = [
        'pais',
        'codigo',
        'estado'
    ];
}
