<div class="card text-white bg-light">
    <div class="card-header">
        <span><ion-icon  class="text-secondary" size="large" name="build-outline"></ion-icon>Mantenimiento preventivo<br/>
        <span class="card-title" id="tot_equipos">{{$totmp}} </span>Pendientes de firma</span>
    </div>
    <div class="card-body text-right">
    @if(count($data['mant_prev']))
        @foreach($data['g_mant_prev'] as $k=>$gmp )
            <div class="chip chip-warning chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                <span class="chip-label">{{$gmp->cliente()->nombre}} </span>
                <i class="chip-icon abrir"  id="gmp{{$gmp->cliente_id}}" >
                <span class=" pull-right flechagmp flechagmp{{$gmp->cliente_id}}"title="Ver mas">
                    @if($k==0 and !$abierta0)
                    <ion-icon name="chevron-down-outline"></ion-icon></span>
                    @else
                    <ion-icon name="chevron-up-outline"></ion-icon></span>
                    @endif
                </i>
            </div>
            @foreach($data['mant_prev']->where('cliente_id',$gmp->cliente_id) as $mp)
            <a href="@if(Sentinel::getUser()->hasAccess('equipos.edit_mant_prev') and false)
                {{ route('equipos.edit_mant_prev',array('id'=>$mp->id)) }}
            @else
                {{ route('equipos.detail',array('id'=>$mp->equipo_id)) }}?show=rows&tab=2
            @endif"  class="chip chip-warning chip-media ml-05 mb-05 gmplist gmp{{$gmp->cliente_id}}" style="width:98%; @if($k!=0 or $abierta0)  display:none; @endif">
                <i class="chip-icon">
                    Ir
                </i>
                <span class="chip-label">{{$mp->equipo()->numero_parte}} </span>
                <span class="fecha pull-right" title="Fecha de creacion">{{transletaDate($mp->created_at,true,'')}}</span>
            </a>
            @endforeach
        @endforeach

    @endif
    </div>
</div>
<br/>
<script>
    $('.abrir').click(function(){
        var id=$(this).attr('id');
        console.log(id);
        var clase='.'+id;
        var flecha='.flecha'+id;
        $('.gmplist').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        $('.flechagmp').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flecha).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');
        console.log(flecha);
    });
</script>