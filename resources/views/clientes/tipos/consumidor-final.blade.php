<div class="card mb-4" id="form-container">
    <div class="card-header">
        <h3>Nuevo Consumidor Final</h3>
    </div>
    <div class="card-body">
        <form id="cliente-form" action="{{ route('clientes.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tipo_cliente" value="1">
            
            <div class="row">
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="id_tipo_documento">Tipo de documento</label>
                        <select class="form-control" id="id_tipo_documento" name="id_tipo_documento" required>
                            <option value="">Seleccione un documento</option>
                            @foreach($tiposDocumento as $tipo)
                                <option value="{{ $tipo->id_tipo_documento }}">{{ $tipo->tipo_documento }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="numero_documento">N° de Documento</label>
                        <input type="text" class="form-control" id="dui_nit" name="dui_nit" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombre">Razón social /Nombre del cliente</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nombre_comercial">Nombre Comercial</label>
                        <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="correo">Correo electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo">
                    </div>
                </div>
                
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" required>
                    </div>
                </div>
            </div>
            <br>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-secondary" onclick="$('#formulario-cliente-container').empty(); $('#tipo_cliente').val('');">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#cliente-form').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            beforeSend: function() {
                form.find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            },
            success: function(response) {
                if(response.success) {
                    window.location.reload(); // Recarga la página para ver los cambios
                }
            },
            error: function(xhr) {
                form.find('button[type="submit"]').prop('disabled', false).html('Guardar');
                
                // Mostrar errores de validación
                var errors = xhr.responseJSON.errors;
                var errorHtml = '<div class="alert alert-danger"><ul>';
                
                $.each(errors, function(key, value) {
                    errorHtml += '<li>' + value + '</li>';
                    // Marcar campo con error
                    form.find('[name="'+key+'"]').addClass('is-invalid');
                });
                
                errorHtml += '</ul></div>';
                
                // Eliminar alertas anteriores
                form.find('.alert-danger').remove();
                form.prepend(errorHtml);
            }
        });
    });

    // Eliminar clase de error al cambiar un campo
    $('#cliente-form input, #cliente-form select').on('change keyup', function() {
        $(this).removeClass('is-invalid');
    });

    // Validación en tiempo real para número de documento (opcional)
    $('#numero_documento').on('input', function() {
        // Puedes agregar validación específica según el tipo de documento seleccionado
        var tipoDoc = $('#id_tipo_documento').val();
        var numeroDoc = $(this).val();
        
        // Ejemplo: Validar que sea numérico para ciertos tipos de documento
        if(tipoDoc == 1 || tipoDoc == 2) { // Suponiendo que 1 y 2 son DUI/NIT
            if(!/^\d+$/.test(numeroDoc)) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        }
    });
});
</script>