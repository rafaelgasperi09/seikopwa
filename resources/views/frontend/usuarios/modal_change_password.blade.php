<!-- Modal Form -->
<div class="modal fade dialogbox show" id="modal_change_password" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Actulizar Contraseña</h5>
            </div>
            {{ Form::model($data, array( 'method' => 'PUT' , 'role' => 'form','class'=>'form-horizontal','id'=>'form_change_password')) }}
            <div class="modal-body text-left mb-2">
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="password">Contraseña</label>
                        {{ Form::password('password',array('class'=>'form-control','placeholder'=>'Contraseña','required','autocomplete'=>'off')) }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>

                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="password_confirmation">Confirmar Contraseña'</label>
                        {{ Form::password('password_confirmation',  array("class" => "form-control",'placeholder'=>'Confirmar Contraseña','required','autocomplete'=>'off'))  }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <button type="button" class="btn btn-text-secondary" data-dismiss="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-text-primary">GUARDAR</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<script>
    $('#modal_change_password').on('show.bs.modal', function (e) {
        var action = $(e.relatedTarget).attr('data-action');
        $("#form_change_password").attr('action',action);
    });
</script>
<!-- * Modal Form -->


