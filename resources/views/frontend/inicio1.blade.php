@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'GMP APP','subtitle'=>'Bienvenido(a) a GMP APP'))
    @php
        $totales_title='Total Equipos';
        $totales=0;
        $totdc=0; $totmp=0;$totstp=0;$totstpia=0;
        if(isset($data['daily_check']) and count($data['daily_check'])){  $totdc=count($data['daily_check']); }
        if(isset($data['mant_prev']) and count($data['mant_prev'])){ $totmp=count($data['mant_prev']);  }
        if(isset($data['serv_tec_a']) and count($data['serv_tec_a'])){ $totsta=count($data['serv_tec_a']);}
        $totsta=0;$totstpr=0;
        if(isset($data['serv_tec_p']) and count($data['serv_tec_p'])){ $totstp=count($data['serv_tec_p']);  }
        if(isset($data['serv_tec_pr']) and count($data['serv_tec_pr'])){ $totstpr=count($data['serv_tec_pr']);  }
        if(isset($data['serv_tec_pi_a']) and count($data['serv_tec_pi_a'])){ $totstpia=count($data['serv_tec_pi_a']);  }
        $abierta0=true;
    @endphp
    <ul class="nav nav-tabs style1 iconed" role="tablist">
       
        <li class="nav-item">
            <a class="nav-link @if($data['tipo']=='cliente') active @endif" href="{{route('inicio')}}?tipo=cliente" >
                    <ion-icon name="person-outline" class="text-primary" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                    CLIENTE
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link @if($data['tipo']=='gmp') active @endif"  href="{{route('inicio')}}?tipo=gmp" >
                    <ion-icon name="people-outline" class="text-info" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                    GMP
            </a>
        </li>
        
    </ul>
    <div class="section mt-2">
        <div class="row">
        @if(current_user()->isOnGroup('operadorC') )
            <div class=" mb-2 col-md-6 col">                   
                {{--}}TOTAL EQUIPOS{{--}}
                @include('frontend.dashboard_widgets.total_equipos')
            </div>
            <div class=" mb-2 col-md-6 col">                   
                {{--}}DAILY CHECK PENDIENTE DE FIRMA {{--}}
                @include('frontend.dashboard_widgets.equipos_pendientes_daily_check')
            </div>
        @else
            <div class=" mb-2 col-md-6 col">
                    {{--}}MANTENIMIENTO PREVENTIVO PENDIENTE DE FIRMA {{--}}
                    @include('frontend.dashboard_widgets.mant_prev_pend_firma')
                
                @if( current_user()->isOnGroup('supervisorc') or
                     current_user()->isOnGroup('supervisor-cliente') or
                     current_user()->isOnGroup('programador'))

                    {{--}}DAILY CHECK PENDIENTE DE FIRMA {{--}}
                    @include('frontend.dashboard_widgets.daily_check_pend_firma')

                    {{--}}EQUIPOS SIN DAILY CHECK {{--}}
                    @include('frontend.dashboard_widgets.equipos_pendientes_daily_check')

                @endif  
            </div>
            @if( $data['tipo']=='cliente' or  !current_user()->isCliente())
            <div class=" mb-2 col-md-6 col">
                {{--}}SOPORTE PENDIENTE DE INICIAR {{--}}
                
                @include('frontend.dashboard_widgets.soporte_pend_iniciar')
                @if( current_user()->isSupervisor() or current_user()->isOnGroup('programador') or current_user()->isOnGroup('administrador') )
                @include('frontend.dashboard_widgets.soporte_pend_tecnico')
                @endif
                {{--}}SOPORTE TECNICO EN PROCESO {{--}}
                @include('frontend.dashboard_widgets.soporte_en_proceso')

                {{--}}ULTIMOS SERVICIO TECNICO CERRADOS{{--}}
                @include('frontend.dashboard_widgets.ultimos_servicio_tecnico_cerrado')
            </div>
            @else
                <div class=" mb-2 col-md-6 col">
                {{--}}SOPORTE TECNICO EN PROCESO {{--}}
                @include('frontend.dashboard_widgets.soporte_en_proceso_cliente')
                </div>
            @endif
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


@stop
