<div class="section mt-2" style="overflow-x:scroll">
    <table class="table table-striped datatable responsive" >
        <thead>
        <tr>
            <th scope="col">Semana</th>
            <th>Lunes (T1)</th>
            <th>Lunes (T2)</th>
            <th>Martes (T1)</th>
            <th>Martes (T2)</th>
            <th>Miercoles (T1)</th>
            <th>Miercoles (T2)</th>
            <th>Jueves (T1)</th>
            <th>Jueves (T2)</th>
            <th>Viernes (T1)</th>
            <th>Viernes (T2)</th>
            <th>Sabado (T1)</th>
            <th>Sabado (T2)</th>
            <th  scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody id="check_table" >
        @foreach($data as $d)
            @php
                $date = \Carbon\Carbon::now();
                $date->setISODate($d->ano,$d->semana);
            @endphp
            <tr>
                <td data-toggle="tooltip" data-placement="right" title="" data-original-title="{{ $date->startOfWeek()->format('Y-m-d') }} a {{ $date->endOfWeek()->format('Y-m-d')  }}" aria-describedby="tooltip112589" >
                    {{ $d->semana }}
                </td>
                <td data-id="{{ $d->Lunes1 }}" data-turno="1">@empty(!$d->Lunes1) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;"  ></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td data-id="{{ $d->Lunes2 }}" data-turno="2">@empty(!$d->Lunes2) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;"  ></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td data-id="{{ $d->Martes1 }}" data-turno="1">@empty(!$d->Martes1) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;"  ></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td data-id="{{ $d->Martes2 }}" data-turno="2">@empty(!$d->Martes2) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;"  ></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td data-id="{{ $d->Miercoles1 }}" data-turno="1">@empty(!$d->Miercoles1) <ion-icon class='checkday' name="checkmark-outline"  size="large"style="color:green;"  ></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td data-id="{{ $d->Miercoles2 }}" data-turno="2">@empty(!$d->Miercoles2) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;"  ></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td data-id="{{ $d->Jueves1 }}" data-turno="1">@empty(!$d->Jueves1) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;"  ></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td data-id="{{ $d->Jueves2 }}" data-turno="2">@empty(!$d->Jueves2) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;"  ></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td data-id="{{ $d->Viernes1 }}" data-turno="1">@empty(!$d->Viernes1) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;"  ></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td data-id="{{ $d->Viernes2 }}" data-turno="2">@empty(!$d->Viernes2) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;"  ></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td data-id="{{ $d->Sabado1 }}" data-turno="1">@empty(!$d->Sabado1) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;"  ></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td data-id="{{ $d->Sabado2 }}" data-turno="2">@empty(!$d->Sabado2) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;"  ></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td>
                    <a href="{{ url('/equipos/reportes/form_montacarga_daily_check/'.$d->id) }}" target="_blank" class="btn btn-primary btn-sm mr-1 ">
                        <ion-icon name="print-outline" title="Ver detalle"></ion-icon>Imprimir
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="modal fade modalbox" id="DailyCheckModal" data-backdrop="static" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daily Check ({{ $equipo->numero_parte }})</h5>
                    <a href="javascript:;" data-dismiss="modal">Cerrar</a>
                </div>
                <div class="modal-body" id="daily_check_modal_body">

                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready( function () {
        var supervisorAccess = false;
            @if(\Sentinel::getUser()->hasAccess('equipos.edit_daily_check'))
                supervisorAccess = true;
            @endif
        $('.datatable').DataTable({
            'order':['0','DESC'],
            "scrollX": true
        });

        $("#check_table > tr > td").click(function() {
            var id = $(this).attr('data-id');
            var turno = $(this).attr('data-turno');
             console.log('turno :'+turno);
            $.ajax({
                url: '{{ url("api/formulario_data") }}',
                dataType: "json",
                data: "formulario_registro_id="+id,
                type: 'get',
                success: function(data) {


                    var html = '';
                    var estatus='';
                    $.each(data.data, function( index, value ) {

                        if(estatus=='')
                            estatus=value.registro.estatus;
                        valor = value.valor;
                        if(index == 1) valor = value.valor+' (Turno '+turno+')';
                        if(valor!=null){
                            if(value.campo.tipo == 'firma') valor = '<img src="../storage/firmas/'+valor+'">';
                            if(value.campo.tipo == 'camera') valor = '<img src="../storage/equipos/'+valor+'" width="100%">';
                        }else{
                            valor='';
                        }

                        html +='<dl class="row">\n' +
                                    '<dt class="col-sm-3">'+value.campo.etiqueta+' :</dt>\n' +
                                    '<dd class="col-sm-9">'+valor+'</dd>\n' +
                               '</dl>'
                    });

                    $('#daily_check_modal_body').html(html);

                    if(supervisorAccess ){
                        console.log(estatus);
                        if(estatus!='C'){
                            $('.modal-footer').html('<a href="daily_check/'+id+'/edit" class="btn btn-success btn-sm"><ion-icon name="create-outline" title="Editar"></ion-icon>Editar</a>');
                        }

                    }
                    if(html.length>0)
                    $('#DailyCheckModal').modal('show');
                }
            });
        });
    });
</script>
