@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Dashboard','subtitle'=>'Bienvenido(a) a GMP APP'))
    @php
    $totales_title='Total Equipos';
   $totales=0;
@endphp
    <div class="section mt-2">
    <div class="row">
        @if(!current_user()->isOnGroup('tecnico'))
        <div class=" mb-2 col-md-6 col @if(current_user()->isOnGroup('tecnico')) d-none @endif">
        <?PHP /****  LISTADO DE TIPOS DE EQUIPOS  ************/?>

            <div class="card text-white bg-light">
                <div class="card-header"><span id="tot_title">Total Equipos </span><h5 class="card-title" id="tot_equipos">0</h5></div>
                <div class="card-body">

                @if(!current_user()->isCliente())
                <div class="chip chip-media ml-05 mb-05" style="width:100%">
                    <div class="col-md-4"><b>Tipo</b></div>
                    <div class="col-md-4 text-center"><b>Clientes</b></div>
                    <div class="col-md-4 text-right"><b>GMP</b></div>
                </div>
                @endif
                @foreach($data['total_equipos'] as $sub_equipos)
                    @foreach($sub_equipos['tipos'] as $tipo)
                    @php $totales+=$tipo['total'];  @endphp
                    <a href="{{ route('equipos.tipo',array($sub_equipos['name'],$tipo['tipo_id'])) }}"
                    class="chip chip-media ml-05 mb-05" style="width:100%">
                        @if(current_user()->isCliente())
                            <i class="chip-icon bg-primary">
                                {{ $tipo['total'] }}
                            </i>
                        @else
                            <i class="chip-center chip-icon bg-primary">
                                {{ $tipo['total'] }}
                            </i>
                            <i class="chip-icon bg-warning" title="GMP">
                                {{ $tipo['GMtotal'] }}
                            </i>
                        @endif
                        <span class="chip-label">{{ $tipo['tipo'] }}</span>
                    </a>
                    @endforeach
                @endforeach
                <?PHP /****  LISTADO DE BATERIAS  ************/?>
                @if(\Sentinel::hasAccess('baterias.index'))
                <a href="{{ route('baterias.index') }}"
                    class="chip chip-media ml-05 mb-05" style="width:100%">
                        <i class="chip-icon bg-success">
                            {{ $data['total_baterias'] }}
                        </i>
                        <span class="chip-label">Baterías</span>
                    </a>
                    @php
                        $totales+=$data['total_baterias'];
                        $totales_title='Total Equipos + Baterías';
                    @endphp
                @endif
                </div>
            </div>
        </div>
        @endif
        <div class=" mb-2 col-md-6 col">
            @if(current_user()->isOnGroup('supervisor') or current_user()->isOnGroup('programador') or current_user()->isOnGroup('supervisorC'))
                @php
                $totdc=0; $totmp=0;$totstp=0;
                if(count($data['daily_check'])){  $totdc=count($data['daily_check']); }
                if(count($data['mant_prev'])){ $totmp=count($data['mant_prev']);  }
                if(count($data['serv_tec_p'])){ $totstp=count($data['serv_tec_p']);}
                @endphp
                <div class="card text-white bg-light">
                    <div class="card-header">
                        <span ><ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon> Daily Check<br/>
                        <span class="card-title" id="tot_equipos">{{$totdc}} </span>Pendientes de firma</span>
                    </div>
                    <div class="card-body">
                    @if(count($data['daily_check']))
                        @foreach($data['daily_check'] as $dc)
                        <a href="@if(Sentinel::getUser()->hasAccess('equipos.edit_daily_check')) {{ route('equipos.edit_daily_check',array('id'=>$dc->id)) }} @else {{ route('equipos.detail',array('id'=>$dc->equipo_id)) }}?show=rows#dailycheck @endif"  class="chip chip-warning chip-media ml-05 mb-05" style="width:100%">
                            <i class="chip-icon">
                                Ir
                            </i>
                            <span class="chip-label">{{$dc->equipo()->numero_parte}} - turno {{$dc->turno_chequeo_diario}}</span>
                            <span class="fecha pull-right" title="Fecha de creacion">{{transletaDate($dc->created_at)}}</span>
                        </a>
                        @endforeach

                    @endif
                    </div>
                </div>
                <br/>
                <div class="card text-white bg-light">
                    <div class="card-header">
                        <span><ion-icon  class="text-secondary" size="large" name="build-outline"></ion-icon>Mantenimiento preventivo<br/>
                        <span class="card-title" id="tot_equipos">{{$totmp}} </span>Pendientes de firma</span>
                    </div>
                    <div class="card-body">
                    @if(count($data['mant_prev']))
                        @foreach($data['mant_prev'] as $mp)
                        <a href="@if(Sentinel::getUser()->hasAccess('equipos.edit_mant_prev'))
                            {{ route('equipos.edit_mant_prev',array('id'=>$mp->id)) }}
                        @else
                            {{ route('equipos.detail',array('id'=>$mp->equipo_id)) }}?show=rows&tab=2
                        @endif"  class="chip chip-warning chip-media ml-05 mb-05" style="width:100%">
                            <i class="chip-icon">
                                Ir
                            </i>
                            <span class="chip-label">{{$mp->equipo()->numero_parte}} </span>
                            <span class="fecha pull-right" title="Fecha de creacion">{{transletaDate($mp->created_at)}}</span>
                        </a>
                        @endforeach

                    @endif
                    </div>
                </div>
                <br/>
                <div class="card text-white bg-light">
                    <div class="card-header">
                        <span ><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte técnico<br/>
                        <span class="card-title" id="tot_equipos">{{$totstp}} </span>Pendientes por asignar técnico</span>
                    </div>
                    <div class="card-body">
                    @if(count($data['serv_tec_p']))
                        @foreach($data['serv_tec_p'] as $st)
                        <a href="{{ route('equipos.detail',array('id'=>$st->equipo()->id)) }}?show=rows&tab=3"  class="chip chip-danger chip-media ml-05 mb-05" style="width:100%">
                            <i class="chip-icon">
                                Ir
                            </i>
                            <span class="chip-label">{{$st->equipo()->numero_parte}} </span>
                            <span class="fecha pull-right" title="Fecha de creacion de ticket">{{transletaDate($st->created_at)}}</span>
                        </a>
                        @endforeach

                    @endif
                    </div>
                    @if(count($data['serv_tec_pi_a']))
                    <div class="card-header">
                        <span class="card-title" id="tot_equipos">{{$totstp}} </span>Pendientes por Iniciar</span>
                    </div>
                    <div class="card-body">
                        @foreach($data['serv_tec_pi_a'] as $st)
                        <a href="{{ route('equipos.detail',array('id'=>$st->equipo()->id)) }}?show=rows&tab=3"  class="chip chip-warning chip-media ml-05 mb-05" style="padding:18px; width:100%">
                            <i class="chip-icon">
                                Ir
                            </i>
                            <span class="chip-label">{{$st->equipo()->numero_parte}} </span>
                            <div class="fecha pull-right"><span title="Fecha de asignacion de tecnico">{{transletaDate($st->fecha_asignacion)}}</span> <br/> <span title="Tecnico asignado"> {{$st->tecnicoAsignado->getFullName()}}</span></div>
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>    <br/>
            @endif
            @if(current_user()->isOnGroup('tecnico') or current_user()->isOnGroup('programador'))
            @php
            $totsta=0;$totstpr=0;
            if(count($data['serv_tec_a'])){ $totsta=count($data['serv_tec_a']);  }
            if(count($data['serv_tec_pr'])){ $totstpr=count($data['serv_tec_pr']);  }
            @endphp
                <div class="card text-white bg-light">
                    <div class="card-header">
                        <span ><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte técnico<br/>
                        <span class="card-title" id="tot_equipos">{{$totsta}} </span>Pendientes de iniciar</span>
                    </div>
                    <div class="card-body">
                    @if(count($data['serv_tec_a']))
                        @foreach($data['serv_tec_a'] as $sta)
                        <a href="{{ route('equipos.detail',array('id'=>$sta->equipo()->id)) }}?show=rows&tab=3"  class="chip chip-danger chip-media ml-05 mb-05" style="width:100%">
                            <i class="chip-icon">
                                Ir
                            </i>
                            <span class="chip-label">{{$sta->equipo()->numero_parte}} </span>
                            <span class="fecha pull-right" title="Fecha de asignacion de tecnico">
                                 {{transletaDate($sta->estatusHistory()->orderBy('created_at','desc')->first()->created_at)}}
                            </span>
                        </a>
                        @endforeach

                    @endif
                    </div>
                </div>
                <br/>
                <div class="card text-white bg-light">
                    <div class="card-header">
                        <span ><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte técnico<br/>
                        <span class="card-title" id="tot_equipos">{{$totstpr}} </span>Por cerrar</span>
                    </div>
                    <div class="card-body">
                    @if(count($data['serv_tec_pr']))
                        @foreach($data['serv_tec_pr'] as $stpr)
                        <a href="{{ route('equipos.detail',array('id'=>$stpr->equipo()->id)) }}?show=rows&tab=3"  class="chip chip-danger chip-media ml-05 mb-05" style="padding:18px;width:100%">
                            <i class="chip-icon">
                                Ir
                            </i>
                            @php
                                $fecha_sta=$stpr->estatusHistory()->orderBy('created_at','desc')->first()->created_at;
                                $date1 = new DateTime($fecha_sta);
                                $date2 = new DateTime(date('Y-m-d h:i:s'));
                                $diff = $date1->diff($date2);
                                // will output 2 days

                                $transcurrido='';
                                if($diff->d)
                                    $transcurrido=$diff->format('%dd %hh %im');
                                else
                                     $transcurrido=$diff->format('%hh %im');

                            @endphp
                            <span class="chip-label">{{$stpr->equipo()->numero_parte}} </span>
                            <div  class="fecha pull-right" >
                                <span title="Fecha de Inicio">
                                     {{transletaDate($fecha_sta)}}
                                </span><br/>
                                <span title="Tiempo transcurrido">
                                     {{$transcurrido}}
                                </span>
                            </div>


                        </a>
                        @endforeach

                    @endif
                    </div>
                </div> <br/>
            @endif
            @if(current_user()->isOnGroup('operadorc') or current_user()->isOnGroup('programador'))
                <div class="card text-white bg-light">
                    <div class="card-header">
                        <span >
                            <ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon>
                            Equipos pendientes por Daily Check<br/>

                    </div>
                    <div class="card-body">
                    @if(count($data['equipos_sin_daily_check_hoy']))
                        @foreach($data['equipos_sin_daily_check_hoy'] as $id=>$equipo)
                            <a href="{{ route('equipos.detail',array('id'=>$id)) }}?show=rows&tab=1" class="chip chip-warning chip-media ml-05 mb-05" >
                                <span class="chip-label">{{$equipo}}</span>
                            </a>
                        @endforeach

                    @endif
                    </div>
                </div>  <br/>
            @endif

            @if(current_user()->isOnGroup('supervisorc') or current_user()->isOnGroup('administrador') or current_user()->isOnGroup('programador'))
                <div class="card text-white bg-light">
                    <div class="card-header">
                        <span >
                            <ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon>
                            Últimos reportes de servicio técnico<br/>
                        </span>
                    </div>
                    <div class="card-body">
                    @if(count($data['serv_tec_10']) and $count=1)
                        @foreach($data['serv_tec_10'] as $st10)

                           <a href="{{ route('equipos.detail',array('id'=>$st10->equipo()->id)) }}?show=rows&tab=3" class="chip  chip-media ml-05 mb-05"  style="width:100%">
                                <i class="chip-icon bg-{!!getStatusBgColor($st10->estatus)!!}">
                                    {{$st10->estatus}}
                                </i>
                                <span class="chip-label">{{$st10->equipo()->numero_parte}}</span>
                            </a>
                        @endforeach

                    @endif
                    </div>
                </div>  <br/>
            @endif

            @if(current_user()->isOnGroup('administrador') or current_user()->isOnGroup('programador'))
                <div class="card text-white bg-light">
                    <div class="card-header">
                        <span>
                            <ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon>
                            Daily Check de equipos
                        </span>
                    </div>
                    <div class="card-body">
                    @if(count($data['global_sin_daily_check_hoy']) )
                        <table class="table table-striped datatable responsive">
                            <thead>
                            <tr>
                                <th scope="col">Cliente</th>
                            </tr>
                            </thead>
                            @foreach($data['global_sin_daily_check_hoy'] as $dce)
                            <tr>
                                <td>
                                    <a href="#" class="chip  chip-media ml-05 mb-05"  style="width:100%;background-color:#b9d1ee">
                                        <i class="chip-icon2 bg-primary">
                                            {{$dce["equipos"]}}
                                        </i>
                                        @if($dce["daily_check"]==0)
                                            <i class="chip-icon bg-danger">
                                                {{$dce["daily_check"]}}
                                            </i>
                                        @elseif($dce["equipos"]>$dce["daily_check"])
                                            <i class="chip-icon bg-warning">
                                                {{$dce["daily_check"]}}
                                            </i>
                                        @else
                                            <i class="chip-icon bg-success">
                                                {{$dce["daily_check"]}}
                                            </i>
                                        @endif
                                        <span class="chip-label">{{$dce["nombre"]}}</span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    @endif
                    </div>
                </div>  <br/>
            @endif

        </div>

    </div>
    <script>
        $('#tot_equipos').html("{{$totales}}");
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
