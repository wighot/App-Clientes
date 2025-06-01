@extends('layout')

@section('content')
    <h1>{{ isset($cliente) ? 'Editar' : 'Crear' }} Cliente</h1>

    <form action="{{ isset($cliente) ? route('clientes.update', $cliente->id_catalogo_cliente) : route('clientes.store') }}" method="POST">
        @csrf
        @if(isset($cliente))
            @method('PUT')
        @endif

        <div class="mb-3">
    <label for="tipo_cliente" class="form-label">Tipo de Cliente</label>
    <select class="form-select" id="tipo_cliente" name="tipo_cliente" required 
        {{ isset($cliente) ? 'disabled' : '' }}>
        <option value="">Seleccione...</option>
        <option value="1" {{ (isset($cliente) && $cliente->tipo_cliente == 1) ? 'selected' : '' }}>Consumidor Final</option>
        <option value="2" {{ (isset($cliente) && $cliente->tipo_cliente == 2) ? 'selected' : '' }}>Empresa</option>
        <option value="3" {{ (isset($cliente) && $cliente->tipo_cliente == 3) ? 'selected' : '' }}>Extranjero</option>
        <option value="4" {{ (isset($cliente) && $cliente->tipo_cliente == 4) ? 'selected' : '' }}>Proveedor</option>
    </select>
    
    <!-- Campo oculto para enviar el valor cuando está deshabilitado -->
    @if(isset($cliente))
        <input type="hidden" name="tipo_cliente" value="{{ $cliente->tipo_cliente }}">
    @endif
</div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre/Razón Social</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $cliente->nombre ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label for="nombre_comercial" class="form-label">Nombre Comercial (opcional)</label>
            <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" value="{{ $cliente->nombre_comercial ?? '' }}">
        </div>

      

        <div class="col-md-4">
                    <label for="dui_nit" class="form-label">N° Documento</label>
                    <input type="text" class="form-control" id="dui_nit" name="dui_nit" value="{{ $cliente->dui_nit ?? '' }}">
                    <small id="documentoHelp" class="form-text text-muted"></small>
                </div>

        <div class="mb-3">
            <label for="nrc" class="form-label">NRC (opcional)</label>
            <input type="text" class="form-control" id="nrc" name="nrc" value="{{ $cliente->nrc ?? '' }}">
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono (opcional)</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $cliente->telefono ?? '' }}">
        </div>

        <div class="mb-3">
            <label for="correo" class="form-label">Correo (opcional)</label>
            <input type="email" class="form-control" id="correo" name="correo" value="{{ $cliente->correo ?? '' }}">
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección (opcional)</label>
            <textarea class="form-control" id="direccion" name="direccion">{{ $cliente->direccion ?? '' }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($cliente) ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
        <br><br>
    </form>

    <script>
        // Mostrar/ocultar campo de documento según tipo de cliente
        document.getElementById('tipo_cliente').addEventListener('change', function() {
            const docGroup = document.getElementById('documento-group');
            if(this.value == '1') { // Consumidor final
                docGroup.style.display = 'none';
            } else {
                docGroup.style.display = 'block';
            }
        });

        // Inicializar visibilidad al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const tipoCliente = document.getElementById('tipo_cliente').value;
            const docGroup = document.getElementById('documento-group');
            if(tipoCliente == '1') {
                docGroup.style.display = 'none';
            } else {
                docGroup.style.display = 'block';
            }
        });
    </script>
@endsection