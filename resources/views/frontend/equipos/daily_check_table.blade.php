<div class="section mt-2" >
    <table class="datatable table  table-stripped responsive" width="100%" style="border:1px solid #ccc;" >
        <thead>
        <tr>
            <th scope="col" rowspan="2"  class="text-center" style=" vertical-align: top;">Semana</th>
            <th  colspan="4" class="text-center">Lunes</th>
            <th  colspan="4"  class="text-center">Martes</th>
            <th  colspan="4"  class="text-center">Miercoles</th>
            <th  colspan="4"  class="text-center">Jueves</th>
            <th  colspan="4"  class="text-center">Viernes</th>
            <th  colspan="4"  class="text-center">Sabado</th>
            <th  scope="col" rowspan="2"  class="text-center" style=" vertical-align: top;">Acciones</th>
        </tr>
        <tr>
        @for($d=0;$d<=5;$d++)
            @for($i=1;$i<=4;$i++)
                <th title="{{$dow[$d]}} Turno {{$i}}">T{{$i}}</th>
            @endfor
        @endfor
        </tr>
        </thead>
        <tbody id="check_table" style="overflow-x:auto">
        @foreach($data as $d)
            @php
                $date = \Carbon\Carbon::now();
                $date->setISODate($d->ano,$d->semana);
            @endphp
            <tr>
                <td data-toggle="tooltip" data-placement="right" title="" data-original-title="{{ $date->startOfWeek()->format('Y-m-d') }} a {{ $date->endOfWeek()->format('Y-m-d')  }}" aria-describedby="tooltip112589" >
                    {{ $d->ano.'-'.$d->semana }}
                </td>
                @foreach($dias as $dia)
                @php
                $turno=(int)filter_var($dia, FILTER_SANITIZE_NUMBER_INT);
                $prioridad= explode('_',$d->{$dia});
                $prioridad=end($prioridad);
                if(!empty($d->{$dia})){
                    if($prioridad=='No usar este equipo')
                        $check='<ion-icon class="checkday" name="close-outline" style="color:#FE9500 ;" size="large"></ion-icon>';
                    else
                        $check=' <ion-icon class="checkday" name="checkmark-outline" size="large" style="color:green;"  ></ion-icon> ';
                }else{
                    $check='<ion-icon name="close-outline" style="color:red;" size="large"></ion-icon> ';
                }
               
                @endphp
                <td data-id="{{ $d->{$dia} }}" data-turno="{{$turno}}">
                   {!!$check!!}
                </td>
                @endforeach
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
            @if(\Sentinel::getUser()->hasAccess('equipos.edit_daily_check') or \Sentinel::hasAccess('equipos.delete_daily_check'))
                supervisorAccess = true;
            @endif
        $('.datatable').DataTable({
            'order':['0','DESC'],
        });

        $(document).on('click',"#check_table > tr > td",function() {
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
                        if(valor!=null && valor!=''){
                            if(value.campo.tipo == 'firma') valor = '<img src="../storage/firmas/'+valor+'">';
                            if(value.campo.tipo == 'camera') valor = '<a href="../storage/equipos/'+valor+'" download><img src="../storage/equipos/'+valor+'" width="100%" download></a>';
                        }else{
                            valor='';
                        }
                        var bglinea='';
                        if(valor=="M" || valor=="R"){
                             var bglinea='bg-danger';
                        }

                        html +='<dl class="row sss '+bglinea+'">\n' +
                                    '<dt class="col-sm-3">'+value.campo.etiqueta+' :</dt>\n' +
                                    '<dd class="col-sm-9">'+valor+'</dd>\n' +
                               '</dl>'
                    });

                    $('#daily_check_modal_body').html(html);

                    if(supervisorAccess ){
                        var buttons='';
                        console.log(estatus);
                        if(estatus!='C'){
                             buttons='\
                            <a href="daily_check/'+id+'/edit" class="btn btn-success btn-sm">\
                                <ion-icon name="create-outline" title="Editar">\
                                </ion-icon>Editar\
                            </a>\
                            ';
                        }
                        @if(\Sentinel::hasAccess('equipos.delete_daily_check') )
                        buttons=buttons+'<a href="daily_check/'+id+'/delete" class="btn btn-danger btn-sm">\
                            <ion-icon name="trash-outline" title="Borrar reporte">\
                            </ion-icon>Borrar\
                            </a>';
                        @endif
                        if(buttons!=''){
                            $('.modal-footer').html(buttons);
                        }
                    }
                    if(html.length>0)
                    $('#DailyCheckModal').modal('show');
                }
            });
        });
    });
</script>
