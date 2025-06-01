@extends('layout')

@section('content')
<div class="container">
    <h1>Detalles del Cliente</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $cliente->nombre }}</h5>
            <p class="card-text">
                <strong>Tipo:</strong> {{ $cliente->tipo_cliente_nombre }}<br>
                <strong>Razón Social:</strong> {{ $cliente->nombre }}<br>
                <strong>Nombre Comercial:</strong> {{ $cliente->nombre_comercial }}<br>
                <strong>Documento:</strong> {{ $cliente->documento_formateado }}<br>
                <strong>NRC:</strong> {{ $cliente->nrc ?? 'N/A' }}<br>
                <strong>Dirección:</strong> {{ $cliente->direccion }}<br>
                <strong>Correo:</strong> {{ $cliente->correo }}<br>
            </p>
            <a href="{{ route('clientes.edit', $cliente->id_catalogo_cliente) }}" class="btn btn-primary">Editar</a>
            <form action="{{ route('clientes.destroy', $cliente->id_catalogo_cliente) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection