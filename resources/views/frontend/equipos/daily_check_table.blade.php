<div class="section mt-2" style="overflow-x:auto">
    <table class="table table-striped datatable responsive">
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
        <tbody>
        @foreach($data as $d)
            @php
                $date = \Carbon\Carbon::now();
                $date->setISODate($d->ano,$d->semana);
            @endphp
            <tr>
                <td data-toggle="tooltip" data-placement="right" title="" data-original-title="{{ $date->startOfWeek()->format('Y-m-d') }} a {{ $date->endOfWeek()->format('Y-m-d')  }}" aria-describedby="tooltip112589" >
                    {{ $d->semana }}
                </td>
                <td>@empty(!$d->Lunes1) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;" data-id="{{ $d->Lunes1 }}" data-turno="1"></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td>@empty(!$d->Lunes2) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;" data-id="{{ $d->Lunes2 }}" data-turno="2"></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td>@empty(!$d->Martes1) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;" data-id="{{ $d->Martes1 }}" data-turno="1"></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td>@empty(!$d->Martes2) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;" data-id="{{ $d->Martes2 }}" data-turno="2"></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td>@empty(!$d->Miercoles1) <ion-icon class='checkday' name="checkmark-outline"  size="large"style="color:green;" data-id="{{ $d->Miercoles1 }}" data-turno="1"></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td>@empty(!$d->Miercoles2) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;" data-id="{{ $d->Miercoles2 }}" data-turno="2"></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td>@empty(!$d->Jueves1) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;" data-id="{{ $d->Jueves1 }}" data-turno="1"></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td>@empty(!$d->Jueves2) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;" data-id="{{ $d->Jueves2 }}" data-turno="2"></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td>@empty(!$d->Viernes1) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;" data-id="{{ $d->Viernes1 }}" data-turno="1"></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td>@empty(!$d->Viernes2) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;" data-id="{{ $d->Viernes2 }}" data-turno="2"></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td>@empty(!$d->Sabado1) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;" data-id="{{ $d->Sabado1 }}" data-turno="1"></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td>@empty(!$d->Sabado2) <ion-icon class='checkday' name="checkmark-outline" size="large" style="color:green;" data-id="{{ $d->Sabado2 }}" data-turno="2"></ion-icon> @else <ion-icon name="close-outline" style="color:red;" size="large"></ion-icon>  @endif</td>
                <td>
                    <a href="{{url('equipos/reportes/form_montacarga_servicio_tecnico/'.$d->id)}}" target="_blank" class="btn btn-primary btn-sm mr-1 ">
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
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready( function () {
        $('.datatable').DataTable({
            'order':['0','DESC']
        });

        $('.checkday').click(function (){
            var id = $(this).attr('data-id');
            var turno = $(this).attr('data-turno');

            $.ajax({
                url: '{{ url("api/formulario_data") }}',
                dataType: "json",
                data: "formulario_registro_id="+id,
                type: 'get',
                success: function(data) {

                    var html = '';

                    $.each(data.data, function( index, value ) {

                        valor = value.valor;
                        if(index == 1) valor = value.valor+' (Turno '+turno+')';
                        html +='<dl class="row">\n' +
                                    '<dt class="col-sm-3">'+value.campo.etiqueta+' :</dt>\n' +
                                    '<dd class="col-sm-9">'+valor+'</dd>\n' +
                               '</dl>'
                    });

                    $('#daily_check_modal_body').html(html);

                    $('#DailyCheckModal').modal('show');
                }
            });
        });
    });
</script>
