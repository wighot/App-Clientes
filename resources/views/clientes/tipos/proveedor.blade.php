<div class="card mb-4" id="form-container">
    <div class="card-header">
        <h3>Nuevo Proveedor</h3>
    </div>
    <div class="card-body">
        <form id="cliente-form" action="{{ route('clientes.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tipo_cliente" value="4"> <!-- 4 = Proveedor -->

            <!-- Documento Section -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="cod_tipo_documento" class="form-label">Documento</label>
                    <select class="form-control" id="cod_tipo_documento" name="cod_tipo_documento" required>
                        <option value="">Seleccione tipo</option>
                        @foreach($tiposDocumento as $tipo)
                            <option value="{{ $tipo->id_tipo_documento }}">{{ $tipo->tipo_documento }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="dui_nit" class="form-label">N° Documento</label>
                    <input type="text" class="form-control" id="dui_nit" name="dui_nit" required>
                    <small id="documentoHelp" class="form-text text-muted"></small>
                </div>
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Razón social / Nombre del cliente</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
            </div>

            <!-- Contacto Section -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono">
                </div>
                <div class="col-md-4">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="correo" name="correo">
                </div>
                <div class="col-md-4">
                    <label for="nombre_comercial" class="form-label">Nombre comercial</label>
                    <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial">
                </div>
            </div>

            <!-- Ubicación Section -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="cod_departamento" class="form-label">Departamento</label>
                    <select class="form-control" id="cod_departamento" name="cod_departamento">
                        <option value="">Seleccione departamento</option>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id_departamento }}">{{ $departamento->departamento }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="cod_municipio" class="form-label">Municipio</label>
                    <select class="form-control" id="cod_municipio" name="cod_municipio" disabled>
                        <option value="">Primero seleccione departamento</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="ciudad" class="form-label">Ciudad</label>
                    <input type="text" class="form-control" id="ciudad" name="ciudad">
                </div>
            </div>

            <!-- Dirección -->
            <div class="form-group mb-4">
                <label for="direccion" class="form-label">Dirección</label>
                <textarea class="form-control" id="direccion" name="direccion" rows="2" required></textarea>
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-secondary" onclick="$('#formulario-cliente-container').empty(); $('#tipo_cliente').val('');">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Manejar el envío del formulario con AJAX
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
    $('#cliente-form input, #cliente-form select, #cliente-form textarea').on('change keyup', function() {
        $(this).removeClass('is-invalid');
    });

    // Cargar municipios cuando cambia el departamento
    $('#cod_departamento').change(function() {
        let departamentoId = $(this).val();
        let municipioSelect = $('#cod_municipio');
        
        if(departamentoId) {
            // Mostrar loading
            municipioSelect.html('<option value="">Cargando...</option>');
            municipioSelect.prop('disabled', false);
            
            // Hacer la petición
            $.get(`/municipios/${departamentoId}`, function(data) {
                if(data.length > 0) {
                    let options = '<option value="">Seleccione municipio</option>';
                    $.each(data, function(index, municipio) {
                        options += `<option value="${municipio.id_municipio}">${municipio.municipio}</option>`;
                    });
                    municipioSelect.html(options);
                } else {
                    municipioSelect.html('<option value="">No hay municipios</option>');
                }
            }).fail(function() {
                municipioSelect.html('<option value="">Error al cargar</option>');
            });
        } else {
            municipioSelect.html('<option value="">Seleccione departamento primero</option>');
            municipioSelect.prop('disabled', true);
        }
    });

    // Formatear DUI/NIT según tipo de documento
    $('#cod_tipo_documento').change(function() {
        const tipoDocumento = $(this).val();
        const documentoHelp = $('#documentoHelp');
        
        if (tipoDocumento === '2') { // DUI
            $('#dui_nit').attr('placeholder', '12345678-9');
            documentoHelp.text('Ejemplo: 12345678-9');
        } else if (tipoDocumento === '3') { // DUI Homologado
            $('#dui_nit').attr('placeholder', '123456789');
            documentoHelp.text('Ejemplo: 123456789');
        } else if (tipoDocumento === '1') { // NIT
            $('#dui_nit').attr('placeholder', '0614-010101-101-4');
            documentoHelp.text('Ejemplo: 0614-010101-101-4 (ingrese sin guiones)');
        } else {
            $('#dui_nit').attr('placeholder', 'N° Documento');
            documentoHelp.text('');
        }
    });

    // Disparar el evento al cargar si hay valor seleccionado
    if ($('#cod_tipo_documento').val()) {
        $('#cod_tipo_documento').trigger('change');
    }
});
</script>