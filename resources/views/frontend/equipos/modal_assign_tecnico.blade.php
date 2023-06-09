<!-- Modal Form -->
<div class="modal fade dialogbox show" id="assign_tecnico_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    {{ Form::model(array(), array('method' => 'PUT' , 'role' => 'form','class'=>'form-horizontal','id'=>'form_assign_tecnico','autocomplete'=>'off')) }}
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asignar Técnico a ticket de soporte</h5>
            </div>
            <div class="modal-body">
                @include('frontend.partials.typeahead',array('field_label'=>'Tecnico','field_name'=>'tecnico_asignado','items'=>getListUsersByRol('tecnico'),'valor_th'=>['',''],'display'=>'block'))
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
    $('#assign_tecnico_modal').on('show.bs.modal', function (e) {
        var action = $(e.relatedTarget).attr('data-action');
        $("#form_assign_tecnico").attr('action',action);
    });
</script>
<!-- * Modal Form -->
