@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Dashboard','subtitle'=>'Bienvenido(a) a GMPCheck'))
    @php 
    $totales_title='Total Equipos';
@endphp   
    <div class="section mt-2">
    <div class="row">
        <div class=" mb-2 col-md-6 col-sm-12 col">
        <?PHP /****  LISTADO DE TIPOS DE EQUIPOS  ************/?>
            <div class="card text-white bg-light">
                <div class="card-header"><span id="tot_title">Total Equipos </span><h5 class="card-title" id="tot_equipos">0</h5></div>
                <div class="card-body">
                @php $totales=0;  @endphp
                @foreach($data['total_equipos'] as $sub_equipos)
                    @foreach($sub_equipos['tipos'] as $tipo)        
                    @php $totales+=$tipo['total'];  @endphp
                    <a href="{{ route('equipos.tipo',array($sub_equipos['name'],$tipo['tipo_id'])) }}" 
                    class="chip chip-media ml-05 mb-05" style="width:100%">
                        <i class="chip-icon bg-primary">
                            {{ $tipo['total'] }}
                        </i>
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
        @php
        $totdc=0; $totmp=0;$totstp=0;
        if(count($data['daily_check'])){  $totdc=count($data['daily_check']); }
        if(count($data['mant_prev'])){ $totmp=count($data['mant_prev']);  }
        if(count($data['serv_tec_p'])){ $totstp=count($data['serv_tec_p']);}
        @endphp
        <div class=" mb-2 col-md-6  col-sm-12 col">
            @if(current_user()->isOnGroup('supervisor') or current_user()->isOnGroup('programador'))
                <div class="card text-white bg-light">
                    <div class="card-header">
                        <span id="tot_title"><ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon> Daily Check<br/>
                        <span class="card-title" id="tot_equipos">{{$totdc}} </span>Pendientes de firma</span>
                    </div>
                    <div class="card-body">
                    @if(count($data['daily_check']))
                        @foreach($data['daily_check'] as $dc)
                        <a href="{{ route('equipos.edit_daily_check',array('id'=>$dc->id)) }}"  class="chip chip-warning chip-media ml-05 mb-05" style="width:100%">
                            <i class="chip-icon">
                                Ir
                            </i>
                            <span class="chip-label">{{$dc->equipo()->numero_parte}} - turno {{$dc->turno_chequeo_diario}}</span>
                        </a>                    
                        @endforeach                     

                    @endif
                    </div>
                </div>     
                <br/>
                <div class="card text-white bg-light">
                    <div class="card-header">
                        <span id="tot_title"><ion-icon  class="text-secondary" size="large" name="build-outline"></ion-icon>Mantenimiento preventivo<br/>
                        <span class="card-title" id="tot_equipos">{{$totmp}} </span>Pendientes de firma</span>
                    </div>
                    <div class="card-body">
                    @if(count($data['mant_prev']))
                        @foreach($data['mant_prev'] as $mp)
                        <a href="{{ route('equipos.edit_mant_prev',array('id'=>$mp->id)) }}"  class="chip chip-warning chip-media ml-05 mb-05" style="width:100%">
                            <i class="chip-icon">
                                Ir
                            </i>
                            <span class="chip-label">{{$mp->equipo()->numero_parte}} </span>
                        </a>                    
                        @endforeach                     

                    @endif
                    </div>
                </div>  
                <br/>
                <div class="card text-white bg-light">
                    <div class="card-header">
                        <span id="tot_title"><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte tecnico<br/>
                        <span class="card-title" id="tot_equipos">{{$totstp}} </span>Pendientes por asignar tecnico</span>
                    </div>
                    <div class="card-body">
                    @if(count($data['serv_tec_p']))
                        @foreach($data['serv_tec_p'] as $st)
                        <a href="{{ route('equipos.detail',array('id'=>$st->equipo()->id)) }}?show=rows&tab=3"  class="chip chip-danger chip-media ml-05 mb-05" style="width:100%">
                            <i class="chip-icon">
                                Ir
                            </i>
                            <span class="chip-label">{{$st->equipo()->numero_parte}} </span>
                        </a>                    
                        @endforeach                     

                    @endif
                    </div>
                </div>   
            @endif  
            @if(current_user()->isOnGroup('tecnico') or current_user()->isOnGroup('programador'))
            @php
            $totsta=0;$totstpr=0;
            if(count($data['serv_tec_a'])){ $totsta=count($data['serv_tec_a']);  }
            if(count($data['serv_tec_pr'])){ $totstpr=count($data['serv_tec_pr']);  }
            @endphp
                <div class="card text-white bg-light">
                    <div class="card-header">
                        <span id="tot_title"><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte tecnico<br/>
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
                        </a>                    
                        @endforeach                     

                    @endif
                    </div>
                </div>     
                <br/>
                <div class="card text-white bg-light">
                    <div class="card-header">
                        <span id="tot_title"><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte tecnico<br/>
                        <span class="card-title" id="tot_equipos">{{$totstpr}} </span>Por cerrar</span>
                    </div>
                    <div class="card-body">
                    @if(count($data['serv_tec_pr']))
                        @foreach($data['serv_tec_pr'] as $stpr)
                        <a href="{{ route('equipos.detail',array('id'=>$stpr->equipo()->id)) }}?show=rows&tab=3"  class="chip chip-danger chip-media ml-05 mb-05" style="width:100%">
                            <i class="chip-icon">
                                Ir
                            </i>
                            <span class="chip-label">{{$stpr->equipo()->numero_parte}} </span>
                        </a>                    
                        @endforeach                     

                    @endif
                    </div>
                </div>                       
            @endif
            @if(current_user()->isOnGroup('operadorc') or current_user()->isOnGroup('programador'))
                <div class="card text-white bg-light">
                    <div class="card-header">
                        <span id="tot_title">
                            <ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon>
                            Equipos pendientes por Daily Check<br/>
        
                    </div>
                    <div class="card-body">
                    @if(count($data['equipos_sin_daily_check_hoy']) and $count=1)
                        @foreach($data['equipos_sin_daily_check_hoy'] as $id=>$equipo)
                            @if( current_user()->isOnGroup('programador') and ($count++<=20))
                            <a href="{{ route('equipos.detail',array('id'=>$id)) }}?show=rows&tab=1" class="chip chip-warning chip-media ml-05 mb-05" >
                                <span class="chip-label">{{$equipo}}</span>
                            </a>       
                            @endif             
                        @endforeach                     

                    @endif
                    </div>
                </div> 
            @endif
        </div>

    </div>
    <script>
        $('#tot_equipos').html("{{$totales}}");
        $('#tot_title').html("{{$totales_title}}");

    </script>
    </div>

@stop
