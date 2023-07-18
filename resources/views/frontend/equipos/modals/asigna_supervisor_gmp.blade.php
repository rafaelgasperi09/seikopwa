<!-- Modal Form -->
<div class="modal fade dialogbox show" id="assign_supervisor" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    {{ Form::model(array(), array('action'=>'EquiposController@assignSupervisorTS','method' => 'POST' , 'role' => 'form','class'=>'form-horizontal','id'=>'form_assign_tecnico','autocomplete'=>'off')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asigna supervisor y fecha #<span id="reporte_id"></span></h5>
            </div>
            <div class="modal-body">
            {{ Form::hidden('sup_formulario_registro_id',null,array('id'=>'sup_formulario_registro_id')) }}
            {{ Form::hidden('redirect_to',url()->current(),array('id'=>'redirect_to')) }}
            {{ Form::hidden('tipo',url()->current(),array('id'=>'tipo')) }}
            @php
            $supervisores=\App\User::whereRaw("id in(SELECT user_id FROM role_users WHERE role_id IN (5,11))")->get()->pluck('full_name','id');
            $supervisores['']='Seleccione';
            @endphp
            {{ Form::select('supervisor_id',$supervisores,current_user()->id,array('class'=>'form-control date','id'=>'supervisor_id')) }}
            <br/>
            {{ Form::date('fecha',\Carbon\Carbon::now()->startOfWeek()->format('d-m-Y'),array('class'=>'form-control date')) }}
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
    $('#assign_supervisor').on('show.bs.modal', function (e) {
        const parametros = window.location.search;
        var redirect=$('#redirect_to').val();
        $('#redirect_to').val(redirect+parametros);
        var repid=$(e.relatedTarget).attr('data-id');
        var reptipo=$(e.relatedTarget).attr('data-tipo');
        $('#tipo_estado').html(reptipo);
        $('#reporte_id').html(repid);
       
        
        console.log(repid);
        $('#sup_formulario_registro_id').val(repid);
        $("#supervisor_id option[value={{current_user()->id}}]").attr("selected",true);
       // $('#supervisor_id').val({{current_user()->id}});
    });
</script>
<!-- * Modal Form -->
