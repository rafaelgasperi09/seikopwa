<!-- Modal Form -->
<div class="modal fade dialogbox show" id="marcar_accidente" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    {{ Form::model(array(), array('action'=>'EquiposController@marcar_accidente','method' => 'POST' , 'role' => 'form','class'=>'form-horizontal','id'=>'form_assign_tecnico','autocomplete'=>'off')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Marcar accidente ? #<span id="reporte_acc_id"></span></h5>
            </div>
            <div class="modal-body">
            {{ Form::hidden('acc_formulario_registro_id',null,array('id'=>'acc_formulario_registro_id')) }}
            {{ Form::hidden('redirect_to',url()->current().'?show=rows&tab=3',array('id'=>'redirect_to')) }}
            {{ Form::select('accidente',['S'=>'SI','N'=>'No'],null,array('class'=>'form-control date','id'=>'accidente',"required"=>"required")) }}
           
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
    $('#marcar_accidente').on('show.bs.modal', function (e) {
        var repid=$(e.relatedTarget).attr('data-id');
        var reptipo=$(e.relatedTarget).attr('data-tipo');
        $('#tipo_estado').html(reptipo);
        $('#reporte_acc_id').html(repid);
       
        
        console.log(repid);
        $('#acc_formulario_registro_id').val(repid);
       // $('#supervisor_id').val({{current_user()->id}});
    });
</script>
<!-- * Modal Form -->
