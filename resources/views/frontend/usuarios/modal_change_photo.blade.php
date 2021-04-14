<!-- Modal Form -->
<div class="modal fade dialogbox show" id="modal_change_photo" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Actulizar foto</h5>
            </div>
            {{ Form::model($data, array( 'method' => 'PUT' , 'role' => 'form','class'=>'form-horizontal','id'=>'form_change_photo','files'=>true)) }}
            <div class="modal-body text-left mb-2">
                <div class="custom-file-upload">
                    {{ Form::file('file',null,array('id'=>'fileuploadInput','accept'=>'.png, .jpg, .jpeg')) }}
                    <label for="fileuploadInput">
                            <span>
                                <strong>
                                    <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" aria-label="cloud upload outline"></ion-icon>
                                    <i>Tap to Upload</i>
                                </strong>
                            </span>
                    </label>
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
    $('#modal_change_photo').on('show.bs.modal', function (e) {
        var action = $(e.relatedTarget).attr('data-action');
        $("#form_change_photo").attr('action',action);
    });
</script>
<!-- * Modal Form -->


