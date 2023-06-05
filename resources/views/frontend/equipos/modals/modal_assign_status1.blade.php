<!-- Modal Form -->
<div class="modal fade dialogbox show" id="assign_status_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    {{ Form::model(array(), array('action'=>'EquiposController@agregar_status','method' => 'POST' , 'role' => 'form','class'=>'form-horizontal','id'=>'form_assign_tecnico','autocomplete'=>'off')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar estado de <span id="tipo_estado"></span></h5>
            </div>
            <div class="modal-body">
            {{ Form::select('equipo_status',[''=>'Seleccione','O'=>'operativo','I'=>'inoperativo'],request('equipo_status'), array("class" => "form-control lista_status","id"=>"equipo_status","required"))  }}
            {{ Form::select('repuesto_status',[''=>'Seleccione','L'=>'listo','E'=>'en espera'],request('repuesto_status'), array("class" => "form-control lista_status","id"=>"repuesto_status","required"))  }}
            {{ Form::hidden('formulario_registro_id',null,array('id'=>'formulario_registro_id')) }}
            {{ Form::hidden('redirect_to',url()->current(),array('id'=>'redirect_to')) }}
            {{ Form::hidden('tipo',url()->current(),array('id'=>'tipo')) }}
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <button type="button" class="btn btn-text-secondary" data-dismiss="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-text-primary">ASIGNAR</button>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>
<script>
    $('#assign_status_modal').on('show.bs.modal', function (e) {
        const parametros = window.location.search;
        var redirect=$('#redirect_to').val();
        $('#redirect_to').val(redirect+parametros);
        var id=$(e.relatedTarget).attr('data-id');
        var tipo=$(e.relatedTarget).attr('data-tipo');
        $('#tipo_estado').html(tipo);
        $('#tipo').val(tipo);
        $('.lista_status').hide();
        $('.lista_status').removeAttr('required');
        $('#'+tipo+'_status').show();
        $('#'+tipo+'_status').attr('required','required');
        
        console.log(id);
        $('#formulario_registro_id').val(id);
    });
</script>
<!-- * Modal Form -->
