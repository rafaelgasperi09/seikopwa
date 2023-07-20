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
                    {{transletaDate($sta->estatusHistory()->orderBy('created_at','desc')->first()->created_at,true,'')}}
            </span>
        </a>
        @endforeach

    @endif
    </div>
</div>
<br/>