<!-- Modal Form -->
<div class="modal fade dialogbox show" id="modal_detalle" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog" role="document" style="max-width:600px !important">
        <div class="modal-content" style="max-width:600px !important">
            <div class="modal-header">
                <h5 class="modal-title">Detalle <span id="detalle_grafico"></span></h5>
            </div>
            <div class="modal-body text-left mb-2" id="detalle_body" style="max-height:600px;overflow-y:auto;overflow-x:auto">

            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <button type="button" class="btn btn-text-secondary" data-dismiss="modal">CERRAR</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<!-- * Modal Form -->
<script>
    @php
    $params='';
    foreach(request()->all() as $k=>$v){
        $params.='&'.$k.'='.$v;
    }

    @endphp
    $('.viewdet').on('click', function (e) {
        var gid = $(this).attr('gid');
        var gtitle = $(this).attr('gtitle');
        $('#detalle_body').html('');
        $('#detalle_grafico').html('');
        $('#detalle_grafico').html(gtitle);
        $.ajax({
                url: '{{ url("/dashboard/cliente/detalle") }}',
                dataType: "html",
                data: "grafica="+gid+"{!!$params!!}",
                type: 'get',
                success: function(data) {
                    $('#detalle_body').html(data);
                }
        });
        $('#modal_detalle').modal('show');
    });
</script>


