<div class="card text-white bg-light">
    <div class="card-header">
        <span ><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte técnico<br/>
        <span class="card-title" id="tot_equipos">{{$totstp}} </span>Pendientes por asignar técnico</span>
    </div>
    <div class="card-body">
    @if(count($data['serv_tec_p']))
        @foreach($data['serv_tec_p'] as $st)
        
            @if( $st->equipo())
            <a href="{{ route('equipos.detail',array('id'=>$st->equipo()->id)) }}?show=rows&tab=3"  class="chip chip-danger chip-media ml-05 mb-05" style="width:100%">
                <i class="chip-icon">
                    Ir
                </i>
                <span class="chip-label">{{$st->equipo()->numero_parte}} </span>
                <span class="fecha pull-right" title="Fecha de creacion de ticket">{{transletaDate($st->created_at,true,'')}}</span>
            </a>
            @endif
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
            <div class="fecha pull-right"><span title="Fecha de asignacion de tecnico">{{transletaDate($st->fecha_asignacion,true,'')}}</span> <br/> <span title="Tecnico asignado"> {{$st->tecnicoAsignado->getFullName()}}</span></div>
        </a>
        @endforeach
    </div>
    @endif
</div>    
<br/>