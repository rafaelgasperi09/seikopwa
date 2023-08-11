<div class="card text-white bg-light">
    <div class="card-header">
        <span ><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte técnico<br/>
        <span class="card-title" id="tot_equipos">{{$totstp}} </span>Pendientes por asignar técnico</span>
    </div>
    <div class="card-body text-right">
    @if(count($data['serv_tec_p']))            
        @foreach($data['g_serv_tec_p'] as $k=>$gst )
            <div class="chip chip-danger chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                <span class="chip-label ">{{$gst->cliente()->nombre}} </span>
                <i class="chip-icon abrirgst"  id="gst{{$gst->cliente_id}}" >
                    <span class=" pull-right flechagst flechagst{{$gst->cliente_id}}"title="Ver mas">
                        @if($k==0)
                        <ion-icon name="chevron-down-outline"></ion-icon></span>
                        @else
                        <ion-icon name="chevron-up-outline"></ion-icon></span>
                        @endif
                </i>
            </div>
            @foreach($data['serv_tec_p']->where('cliente_id',$gst->cliente_id) as $st)
                @if( $st->equipo())
                <a href="{{ route('equipos.detail',array('id'=>$st->equipo()->id)) }}?show=rows&tab=3" 
                 class="chip chip-danger chip-media ml-05 mb-05 gstlist gst{{$gst->cliente_id}}" style="width:98%; @if($k!=0)  display:none; @endif">
                    <i class="chip-icon">
                        Ir
                    </i>
                    <span class="chip-label">{{$st->equipo()->numero_parte}} </span>
                    <span class="fecha pull-right" title="Fecha de creacion de ticket">{{transletaDate($st->created_at,true,'')}}</span>
                </a>
                @endif
            @endforeach
        @endforeach

    @endif
    </div>
    
</div>    
<br/>
<script>
    $('.abrirgst').click(function(){
      
        var id=$(this).attr('id');
        var clase='.'+id;
        var flechagst='.flecha'+id;
        $('.gstlist').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        $('.flechagst').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flechagst).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');

    });
</script>