<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Models\Municipio;

Route::redirect('/', '/clientes');
// Ruta para AJAX de formularios
Route::get('clientes/get-formulario/{tipo}', [ClienteController::class, 'getFormularioPorTipo'])
     ->name('clientes.get-formulario');

// Ruta para el AJAX de municipios
Route::get('/municipios/{departamentoId}', function($departamentoId) {
    $municipios = Municipio::where('cod_mh_departamento', $departamentoId)->get();
    return response()->json($municipios);
})->name('municipios.por.departamento');

// Rutas principales para clientes (CRUD)
Route::resource('clientes', ClienteController::class);

// Rutas para los formularios específicos de cada tipo de cliente
Route::prefix('clientes/crear')->group(function() {
    Route::get('consumidor-final', [ClienteController::class, 'createConsumidorFinal'])
         ->name('clientes.create.consumidor-final');
         
    Route::get('empresa', [ClienteController::class, 'createEmpresa'])
         ->name('clientes.create.empresa');
         
    Route::get('extranjero', [ClienteController::class, 'createExtranjero'])
         ->name('clientes.create.extranjero');
         
    Route::get('proveedor', [ClienteController::class, 'createProveedor'])
         ->name('clientes.create.proveedor');
});

// Ruta para procesar la selección del tipo de cliente
Route::post('clientes/select-tipo', [ClienteController::class, 'selectTipo'])
     ->name('clientes.select-tipo');

Route::get('/clientes/get-formulario/{tipo}/{id?}', [ClienteController::class, 'getFormularioTipo']);


// Ruta para actualizar clientes existentes
Route::put('/clientes/{id}', [ClienteController::class, 'update'])
     ->where('id', '[0-9]+')
     ->name('clientes.update');

Route::get('/clientes/autocomplete', [ClienteController::class, 'autocomplete']);

