@extends('layout')

@section('content')
<div class="container">
    <h1>Clientes</h1>

    <div class="card mb-4">
        <div class="card-header">
            <h2>Tipo de cliente</h2>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="tipo_cliente">Seleccione un tipo de cliente:</label>
                <select class="form-control" id="tipo_cliente" name="tipo_cliente" required>
                    <option value="" selected disabled>Seleccione una opción</option>
                    <option value="1">Consumidor final</option>
                    <option value="2">Empresa</option>
                    <option value="3">Extranjero</option>
                    <option value="4">Proveedor</option>
                </select>
            </div>
            <button id="cargar-formulario" class="btn btn-primary mt-3">Agregar cliente</button>
        </div>
    </div>

    <!-- Contenedor para el formulario dinámico -->
    <div id="formulario-cliente-container" class="mb-4">
        <!-- Aquí se cargará el formulario mediante AJAX -->
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
    <h2>Listado de Clientes</h2>
    <div class="d-flex">
        <!-- Buscador -->
    
        <form method="GET" action="{{ route('clientes.index') }}" class="form-inline me-3">
            <div class="input-group"  style="width: 400px;" >
                <input type="text" name="search" class="form-control me-6" placeholder="Buscar por nombre comercial..." 
                       value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary ms-2" type="submit"> Buscar
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary ms-2" title="Resetear búsqueda">
                <i class="fas fa-redo"></i>
            </a>
                </div>
            </div>
        </form>
        <br><br>
        <!-- Selector de registros por página -->
        <form method="GET" action="{{ route('clientes.index') }}" class="form-inline">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <label for="perPage" class="mr-2">Registros:</label>
            <select name="perPage" id="perPage" class="custom-select" onchange="this.form.submit()">
                <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('perPage') == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </form>
    </div>
</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Razón Social</th>
                            <th>Nombre Comercial</th>
                            <th>Dirección</th>
                            <th>Documento</th>
                            <th>NRC</th>
                            <th>Correo</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->nombre_comercial }}</td>
                            <td>{{ $cliente->direccion }}</td>
                            <td>{{ $cliente->documento_formateado }}</td>
                            <td>{{ $cliente->nrc ?? 'N/A' }}</td>
                            <td>{{ $cliente->correo }}</td>
                            <td class="text-nowrap">
    <a href="{{ route('clientes.edit', $cliente->id_catalogo_cliente) }}" 
       class="btn btn-sm btn-primary" 
       title="Editar">
       <i class="fas fa-edit"></i>
    </a>
    
    <form action="{{ route('clientes.destroy', $cliente->id_catalogo_cliente) }}" 
          method="POST" 
          style="display: inline;"
          onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')">
        @csrf
        @method('DELETE')
        <button type="submit" 
                class="btn btn-sm btn-danger"
                title="Eliminar">
                <i class="fas fa-trash-alt"></i>
        </button>
    </form>
</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
<!-- Mostrar la paginación -->
<div class="d-flex justify-content-center mt-4">
    {{ $clientes->appends([
        'search' => request('search'),
        'perPage' => request('perPage')
    ])->onEachSide(1)->links('pagination::bootstrap-4') }}
</div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#cargar-formulario').click(function() {
        var tipoCliente = $('#tipo_cliente').val();
        
        if (!tipoCliente) {
            alert('Por favor seleccione un tipo de cliente');
            return;
        }

        // Mostrar spinner de carga
        $('#formulario-cliente-container').html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Cargando...</span></div></div>');

        // Hacer la petición AJAX
        $.ajax({
            url: '/clientes/get-formulario/' + tipoCliente,
            type: 'GET',
            success: function(response) {
                $('#formulario-cliente-container').html(response);
            },
            error: function(xhr) {
                $('#formulario-cliente-container').html('<div class="alert alert-danger">Error al cargar el formulario</div>');
            }
        });
    });
});
</script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>

<script>
$(document).ready(function() {
    // Autocompletado
    $('input[name="search"]').typeahead({
        source: function(query, process) {
            return $.get('/clientes/autocomplete', { query: query }, function(data) {
                return process(data);
            });
        },
        minLength: 2
    });
});
</script>

<script>
$(document).ready(function() {
    const searchInput = $('#searchInput');
    const searchResetButton = $('#searchResetButton');
    const buttonText = $('#buttonText');
    const searchForm = searchInput.closest('form');
    
    // Función para actualizar el estado del botón
    function updateButtonState() {
        if (searchInput.val().trim() !== '') {
            buttonText.text('Resetear');
            searchResetButton.find('i').removeClass('fa-search').addClass('fa-times');
        } else {
            buttonText.text('Buscar');
            searchResetButton.find('i').removeClass('fa-times').addClass('fa-search');
        }
    }
    
    // Actualizar estado inicial
    updateButtonState();
    
    // Manejar el evento click del botón
    searchResetButton.on('click', function() {
        if (searchInput.val().trim() !== '') {
            // Si hay texto, resetear
            searchInput.val('');
            // También eliminamos el parámetro search de la URL
            const url = new URL(window.location.href);
            url.searchParams.delete('search');
            window.location.href = url.toString();
        } else {
            // Si no hay texto, enviar el formulario de búsqueda
            searchForm.submit();
        }
    });
    
    // Actualizar el botón cuando cambia el input
    searchInput.on('input', updateButtonState);
});
</script>
<style>
    .search-button {
        margin-left: 8px !important;
        padding: 6px 12px !important;
    }
    
    .forms-container {
        gap: 15px; /* Espacio entre los formularios */
    }
</style>
<style>
    
    /* Estilo para el select con flecha */
    .custom-select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        padding-right: 2.5rem !important;
        text-align: center;
    }

    /* Reducir tamaño de la paginación */
    .pagination {
        font-size: 0.875rem;
    }
    
    .pagination .page-link {
        padding: 0.25rem 0.5rem;
    }
    
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        border-radius: 0.2rem;
    }
</style>

@endsection