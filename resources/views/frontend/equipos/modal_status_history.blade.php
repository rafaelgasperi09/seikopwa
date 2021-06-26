<!-- Modal Form -->
<div class="modal fade modalbox" id="status_history_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Historico Estatus</h5>
                <button type="button" class="btn btn-outline-secondary rounded shadowed mr-1 mb-1 right" data-dismiss="modal">Cerrar</button>
            </div>
            <div class="modal-body">
                <h4 class="modal-subtitle"></h4>

                <div class="wide-block">
                    <!-- timeline -->
                    <div class="timeline timed" id="timeline2">

                    </div>
                    <!-- * timeline -->
                    <div class="gallery" id="gallery">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <!---<button type="button" class="btn btn-text-secondary" data-dismiss="modal">CANCELAR</button>-->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#status_history_modal').on('show.bs.modal', function (e) {
        var id = $(e.relatedTarget).attr('data-id');
        var path='{{ url('/') }}'

        $.ajax({
            url: '{{ url("api/formulario_registro_estatus") }}',
            dataType: "json",
            data: "formulario_registro_id="+id,
            type: 'get',
            success: function(data) {
                console.log(data);
                var html = '';
                var estatus='';
                $.each(data.data, function( index, value ) {

                    var d = new Date(value.created_at);

                    var curr_date = d.getDate();
                    var curr_month = d.getMonth()+1;
                    var curr_year = d.getFullYear();

                    var usuario= value.user.first_name+' '+value.user.last_name;
                    html2='';
                    html += '<div class="item">';
                    html +='<span class="time">'+curr_date+"-"+curr_month+"-"+curr_year+'<br/>'+d.toLocaleTimeString()+'</span>';
                    var estatus = '';
                    var mensaje ='';
                    if(value.estatus == 'P'){
                        html +='<div class="dot bg-warning"></div>\n';
                        estatus = 'PENDIENTE';
                        mensaje = 'Nuevo ticket de servicio tecnico creado por '+usuario;
                    }else if(value.estatus == 'A'){
                        html +='<div class="dot bg-success"></div>\n';
                        estatus = 'ASIGNADA';
                        mensaje = usuario+' asigno ticket de servico tecnico a '+value.registro.tecnico_asignado.first_name+' '+value.registro.tecnico_asignado.last_name;
                    }else if(value.estatus == 'PR'){
                        html +='<div class="dot bg-primary"></div>\n';
                        estatus = 'INICIADA';
                        mensaje = usuario+' dio inicio a tareas de soporte tecnico ';
                    }else if(value.estatus == 'C'){
                        html +='<div class="dot bg-secundary"></div>\n';
                        estatus = 'CERRADO';
                        mensaje = usuario+' dio por finalizada las tareas de soporte tecnico ';
                    }


                    html +='<div class="content">\n';
                    html +='<h4 class="title">'+estatus+'</h4>\n';
                    html +='<div class="text">'+mensaje+'</div>\n';

                    html +='</div>\n';
                    html +='</div>\n';

                });

                $.each(data.data[0].registro.files, function( index, value ) {
                    console.log(value);
                    html2 +='<div class="col-4 mb-2">\n';
                        html2 +='<img src="'+path+'/'+value.ruta+'" alt="image" class="imaged w-100">\n';
                    html2 +='</div>\n';
                });
                $('.timeline').html(html);
                $('#gallery').html(html2);
            }
        });
    });

    function prettyDate2(time){
        var date = new Date(parseInt(time));
        var localeSpecificTime = date.toLocaleTimeString();
        return localeSpecificTime.replace(/:\d+ /, ' ');
    }
</script>
<!-- * Modal Form -->
