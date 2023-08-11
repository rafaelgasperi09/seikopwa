<div class="card text-white bg-light">
    <div class="card-header">
        <span ><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte técnico<br/>
        <span class="card-title" id="tot_equipos">{{$totstpia}} </span title="Se muestra la lista al tecnico asignado">Pendientes de iniciar</span>
    </div>
    <div class="card-body text-right">
    @if(count($data['serv_tec_pi_a']))
        @foreach($data['g_serv_tec_pi_a'] as $k=>$gsta)
            <div class="chip chip-danger chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                <span class="chip-label ">{{$gsta->cliente()->nombre}} </span>
                <i class="chip-icon abrirsta"  id="sta{{$gsta->cliente_id}}" >
                    <span class=" pull-right flechasta flechasta{{$gsta->cliente_id}}"title="Ver mas">
                        @if($k==0 and !$abierta0)
                        <ion-icon name="chevron-down-outline"></ion-icon></span>
                        @else
                        <ion-icon name="chevron-up-outline"></ion-icon></span>
                        @endif
                </i>
            </div>
            @foreach($data['serv_tec_pi_a']->where('cliente_id',$gsta->cliente_id) as $sta)
            <a href="{{ route('equipos.detail',array('id'=>$sta->equipo()->id)) }}?show=rows&tab=3"
              class="chip chip-danger chip-media ml-05 mb-05 stalist sta{{$gsta->cliente_id}}" style="width:98%; @if($k!=0 or $abierta0)  display:none; @endif">
                <i class="chip-icon">
                    Ir
                </i>
                <span class="chip-label">{{$sta->equipo()->numero_parte}} </span>
                <span class="fecha pull-right" title="Fecha de asignacion de tecnico">
                        {{transletaDate($sta->estatusHistory()->orderBy('created_at','desc')->first()->created_at,true,'')}}
                </span>
            </a>
            @endforeach
        @endforeach

    @endif
    </div>
</div>
<br/>
<script>
    $('.abrirsta').click(function(){
      console.log('abrir');
        var id=$(this).attr('id');
        var clase='.'+id;
        var flechasta='.flecha'+id;
        console.log(flechasta);
        $('.stalist').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        $('.flechasta').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flechasta).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');

    });
</script>