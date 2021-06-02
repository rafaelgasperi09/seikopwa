<div class="modal fade dialogbox" id="deleteModal" data-backdrop="static" tabindex="-1" aria-hidden="true" style="display: none;">
    <form method="POST" action="" role="form" class="form" id="form-delete">
    <input name="_method" type="hidden" value="DELETE">
    <input name="_token" value="{{ csrf_token() }}" type="hidden">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar</h5>
            </div>
            <div class="modal-body" id="delete-body">

            </div>
            <div class="modal-footer">
                <div class="btn-list">
                    <button type="submit" class="btn btn-text-primary btn-block">ACEPTAR</button>
                    <a href="#" class="btn btn-text-secondary btn-block" data-dismiss="modal">CANCELAR</a>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
<script>
    $('#deleteModal').on('show.bs.modal', function (e) {
        var action = $(e.relatedTarget).attr('data-action');
        var message = $(e.relatedTarget).attr('data-message');
        $('#form-delete').attr('action',action);
        $('#delete-body').html(message);
    });
</script>
