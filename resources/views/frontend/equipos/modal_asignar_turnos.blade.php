<!-- Modal Form -->
<div class="modal fade dialogbox show" id="assign_turno_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    {{ Form::model($data, array('route' => array('equipos.asignar_turno', $data->id),'method' => 'POST' , 'role' => 'form','class'=>'form-horizontal','id'=>'form_assign_turno','autocomplete'=>'off')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asignar turnos a equipo</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Turnos </label>
                            {{ Form::select('turnos',[1=>1,2=>2,3=>3,4=>4],$data->turnos,array('class'=>'form-control','id'=>'turnos')) }} 
                        </div>
                    </div>
                </div>
           
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
    /*
    $('#assign_tecnassign_turno_modalico_modal').on('show.bs.modal', function (e) {
        var action = $(e.relatedTarget).attr('data-action');
        $("#form_assign_tecnico").attr('action',action);
    });*/
</script>
<!-- * Modal Form -->
