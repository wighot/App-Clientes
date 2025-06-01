<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
    $table->id('id_catalogo_cliente');
    $table->integer('cod_tipo_documento')->nullable();
    $table->string('dui_nit')->nullable();
    $table->string('nrc')->nullable();
    $table->string('nombre');
    $table->string('nombre_comercial')->nullable();
    $table->string('telefono')->nullable();
    $table->string('correo')->nullable();
    $table->string('direccion')->nullable();
    $table->string('ciudad')->nullable();
    $table->string('region')->nullable();
    $table->integer('cod_actividad_economica')->nullable();
    $table->integer('cod_departamento')->nullable();
    $table->integer('cod_municipio')->nullable();
    $table->integer('fk_id_tipo_contribuyente')->nullable();
    $table->integer('tipo_persona')->nullable();
    $table->integer('fk_id_pais')->nullable();
    $table->text('descripcion_adicional')->nullable();
    $table->integer('tipo_cliente')->comment('1=consumidor_final, 2=creditos_fiscales, 3=extranjeros, 4=proveedores');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};
