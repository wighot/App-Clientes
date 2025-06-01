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
        // En la migración
Schema::create('venta_mh_pais', function (Blueprint $table) {
    $table->id('id_pais');
    $table->string('pais');
    $table->string('codigo')->nullable();
    $table->string('estado')->default('1');
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
        Schema::dropIfExists('pais');
    }
};
