<div class="card text-white bg-light">
    <div class="card-header">
        <span ><ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon> Daily Check<br/>
        <span class="card-title" id="tot_equipos">{{$totdc}} </span>Pendientes de firma</span>
    </div>
    <div class="card-body  text-right">
    @if(count($data['daily_check']))
        @foreach($data['g_daily_check'] as $k=>$gdc )
            <div class="chip chip-warning chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                <span class="chip-label ">{{$gdc->cliente()->nombre}} </span>
                <i class="chip-icon abrirgdc"  id="gdc{{$gdc->cliente_id}}" >
                    <span class=" pull-right flechagdc flechagdc{{$gdc->cliente_id}}"title="Ver mas">
                        @if($k==0 and !$abierta0)
                        <ion-icon name="chevron-down-outline"></ion-icon></span>
                        @else
                        <ion-icon name="chevron-up-outline"></ion-icon></span>
                        @endif
                </i>
            </div>
            @foreach($data['daily_check']->where('cliente_id',$gdc->cliente_id) as $dc)
                @if($dc->equipo())
                <a href="@if(Sentinel::getUser()->hasAccess('equipos.edit_daily_check')) {{ route('equipos.edit_daily_check',array('id'=>$dc->id)) }} @else {{ route('equipos.detail',array('id'=>$dc->equipo_id)) }}?show=rows#dailycheck @endif" 
                 class="chip chip-warning chip-media ml-05 mb-05 gdclist gdc{{$gdc->cliente_id}}" style="width:98%;@if($k!=0 or $abierta0)  display:none; @endif">
                    <i class="chip-icon">
                        Ir
                    </i>
                    <span class="chip-label">{{$dc->equipo()->numero_parte}} - turno {{$dc->turno_chequeo_diario}}</span>
                    <span class="fecha pull-right" title="Fecha de creacion">{{transletaDate($dc->created_at,true,'')}}</span>
                </a>
                @endif
            @endforeach
        @endforeach
    @endif
    </div>
</div>
<br/>
<script>
    $('.abrirgdc').click(function(){
        var id=$(this).attr('id');
        var clase='.'+id;
        var flechagdc='.flecha'+id;
        $('.gdclist').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        console.log(flechagdc);
        $('.flechagdc').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flechagdc).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');

    });
</script>