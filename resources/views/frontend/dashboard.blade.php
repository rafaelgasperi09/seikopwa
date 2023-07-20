@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Dashboard','subtitle'=>'Bienvenido(a) a GMP APP'))
    @php
        $totales_title='Total Equipos';
        $totales=0;
    @endphp
    <ul class="nav nav-tabs style1 iconed" role="tablist">
       
        <li class="nav-item">
            <a class="nav-link active show" data-toggle="tab" href="#cliente_tab" role="tab" aria-selected="true">
                    <ion-icon name="person-outline" class="text-primary" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                    CLIENTE
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link   " data-toggle="tab" href="#mant_prev" role="tab" aria-selected="true">
                    <ion-icon name="people-outline" class="text-info" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                    GMP
            </a>
        </li>
        
    </ul>
    <div class="section mt-2">
    <div class="row">
        @if(!current_user()->isOnGroup('tecnico'))
             {{--}}LISTADO DE EQUIPOS Y BATERIAS {{--}}
            @include('frontend.dashboard_widgets.total_equipos')
        @endif
       
        <div class=" mb-2 col-md-6 col">
            @if( current_user()->isOnGroup('supervisorc') or  current_user()->isOnGroup('programador'))
                @php
                    $totdc=0; $totmp=0;$totstp=0;
                    if(count($data['daily_check'])){  $totdc=count($data['daily_check']); }
                    if(count($data['mant_prev'])){ $totmp=count($data['mant_prev']);  }
                    if(count($data['serv_tec_p'])){ $totstp=count($data['serv_tec_p']);}
                @endphp

                {{--}}DAILY CHECK PENDIENTE DE FIRMA {{--}}
                @include('frontend.dashboard_widgets.daily_check_pend_firma')

                {{--}}MANTENIMIENTO PREVENTIVO PENDIENTE DE FIRMA {{--}}
                @include('frontend.dashboard_widgets.mant_prev_pend_firma')

                {{--}}MANTENIMIENTO PREVENTIVO PENDIENTE DE FIRMA {{--}}
                @include('frontend.dashboard_widgets.soporte_pend_tecnico')
                
            @endif
            @if(current_user()->isOnGroup('tecnico') or current_user()->isOnGroup('programador'))

                @php
                    $totsta=0;$totstpr=0;
                    if(count($data['serv_tec_a'])){ $totsta=count($data['serv_tec_a']);  }
                    if(count($data['serv_tec_pr'])){ $totstpr=count($data['serv_tec_pr']);  }
                @endphp

                {{--}}SOPORTE PENDIENTE DE INICIAR {{--}}
                @include('frontend.dashboard_widgets.soporte_pend_iniciar')
                
                {{--}}SOPORTE PENDIENTE DE INICIAR {{--}}
                @include('frontend.dashboard_widgets.soporte_pend_iniciar')
                
                
            @endif
            @if(current_user()->isOnGroup('operadorc') or current_user()->isOnGroup('programador'))

                {{--}}EQUIPOS SIN DAILY CHECK {{--}}
                @include('frontend.dashboard_widgets.equipos_pendientes_daily_check')

            @endif

            @if(current_user()->isOnGroup('supervisorc') or current_user()->isOnGroup('administrador') or 
                current_user()->isOnGroup('programador') or current_user()->isSupervisor())
                
                {{--}}ULTIMOS SERVICIO TECNICO{{--}}
                @include('frontend.dashboard_widgets.ultimos_servicio_tecnico')

            @endif

            @if(current_user()->isOnGroup('administrador') or current_user()->isOnGroup('programador'))
               
                {{--}}DAILY CHECK EQUIPOS{{--}}
                @include('frontend.dashboard_widgets.daily_check_equipos')


            @endif

        </div>

    </div>
    <script>
     
        $('#tot_title').html("{{$totales_title}}");
        $(document).ready( function () {
            $('.datatable').DataTable({
                /*"bFilter": false,*/
                /*"lengthChange": false*/
                language: { search: "" },
            });
        } );
    </script>
    </div>

@stop
