@extends('frontend.main-layout')
@section('css')
  {{ Html::style('assets/css/fullcalendar.css') }}
@stop

@section('content')
    @include('frontend.partials.title',array('title'=>'Calendario','subtitle'=>''))
    <div class="section full mt-2 mb-2">
        <div class="section-title">Servicio Tecnico</div>
        <div class="wide-block pb-3 pt-2">
            <div id="calendar">

            </div>
        </div>
    </div>
    @include('frontend.equipos.modal_status_history')
    <script>
        var myCalendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next',
                center: 'title',
                right: 'today'
            },
            //defaultView:'basicWeek',
            editable: false,
            droppable: false,
            selectable: false,
            selectHelper: true,
            events: [
            @foreach($eventos as $evento)
            {
                id: '{{ $evento['id'] }}',
                title:'{{ $evento['equipo'] }} ({{ $evento['cliente'] }})',
                start:'{{ $evento['inicio'] }}',
                end:'{{ $evento['fin'] }}',
                @if($evento['estatus'] == "P")
                color:'#FE9500',
                @elseif($evento['estatus'] == "C")
                color:'#34C759',
                @elseif($evento['estatus'] == "PR")
                color:'#1E74FD',
                @elseif($evento['estatus'] == "C")
                color:'#A1A1A2',
                @endif
            },
            @endforeach
            ],
            eventClick:  function(event, jsEvent, view) {


                $('.modal-subtitle').html(event.title);
                return new Promise((resolve, reject) => {

                    $.ajax({
                        url: '{{ url("api/formulario_registro_estatus") }}',
                        dataType: "json",
                        data: "formulario_registro_id="+event.id,
                        type: 'get',
                        success: function(data) {
                            resolve(fillTimelineData(data));
                        },error: function (error) {
                            reject(error)
                        },
                    });

                    $('#status_history_modal').modal()
                });

            }

        });

        function fillTimelineData(data){
            var html = '';

            $.each(data.data, function( index, value ) {

                var d = new Date(value.created_at);

                var curr_date = d.getDate();
                var curr_month = d.getMonth()+1;
                var curr_year = d.getFullYear();

                var usuario= value.user.first_name+' '+value.user.last_name;
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

            $('.timeline').html(html);


        }
    </script>
@stop

