<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'venta_catalogo_cliente';

    /**
     * Nombre de la clave primaria.
     *
     * @var string
     */
    protected $primaryKey = 'id_catalogo_cliente';

    /**
     * Indica si los IDs son autoincrementales.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'cod_tipo_documento',
        'dui_nit',
        'nrc',
        'nombre',
        'nombre_comercial',
        'telefono',
        'correo',
        'direccion',
        'ciudad',
        'region',
        'cod_actividad_economica',
        'cod_departamento',
        'cod_municipio',
        'fk_id_tipo_contribuyente',
        'tipo_persona',
        'fk_id_pais',
        'descripcion_adicional',
        'tipo_cliente'
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'tipo_cliente' => 'integer',
        'cod_tipo_documento' => 'integer',
        'cod_actividad_economica' => 'integer',
        'cod_departamento' => 'integer',
        'cod_municipio' => 'integer',
        'fk_id_tipo_contribuyente' => 'integer',
        'tipo_persona' => 'integer',
        'fk_id_pais' => 'integer'
    ];

    /**
     * Obtiene el tipo de documento del cliente.
     */
    public function tipoDocumento()
    {
        return $this->belongsTo('App\Models\TipoDocumento', 'cod_tipo_documento', 'id_tipo_documento');
    }

    /**
     * Obtiene el departamento del cliente.
     */
    public function departamento()
    {
        return $this->belongsTo('App\Models\Departamento', 'cod_departamento', 'id_departamento');
    }

    /**
     * Obtiene el municipio del cliente.
     */
    public function municipio()
    {
        return $this->belongsTo('App\Models\Municipio', 'cod_municipio', 'id_municipio');
    }

    /**
     * Obtiene el tipo de contribuyente del cliente.
     */
    public function tipoContribuyente()
    {
        return $this->belongsTo('App\Models\TipoContribuyente', 'fk_id_tipo_contribuyente', 'id_tipo_contribuyente');
    }

    /**
     * Obtiene la actividad económica del cliente.
     */
    public function actividadEconomica()
    {
        return $this->belongsTo('App\Models\ActividadEconomica', 'cod_actividad_economica', 'id_actividad_economica');
    }

    /**
     * Scope para filtrar por tipo de cliente.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $tipo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_cliente', $tipo);
    }

    /**
     * Obtiene el nombre del tipo de cliente.
     *
     * @return string
     */
    public function getTipoClienteNombreAttribute()
    {
        switch ($this->tipo_cliente) {
            case 1: return 'Consumidor Final';
            case 2: return 'Empresa';
            case 3: return 'Extranjero';
            case 4: return 'Proveedor';
            default: return 'Desconocido';
        }
    }

    /**
     * Formatea el documento según su tipo.
     *
     * @return string
     */
    public function getDocumentoFormateadoAttribute()
    {
        if ($this->cod_tipo_documento == 2 && strlen($this->dui_nit) == 9) {
            // Formatear DUI con guión (12345678-9)
            return substr($this->dui_nit, 0, 8) . '-' . substr($this->dui_nit, 8, 1);
        }
        return $this->dui_nit;
    }
}