<div class="card text-white bg-light">
    <div class="card-header">
        <span >
            <ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon>
            Últimos reportes de servicio técnico cerrados<br/>
        </span>
    </div>
    <div class="card-body text-right">
    @if(count($data['serv_tec_10']) and $count=1)
        @foreach($data['g_serv_tec_10'] as $k=>$gst10)
            <div class="chip chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                <span class="chip-label ">{{$gst10->cliente()->nombre}} </span>
                <i class="chip-icon abrirgst10"  id="gst10{{$gst10->cliente_id}}" >
                    <span class=" pull-right flechagst10 flechagst10{{$gst10->cliente_id}}"title="Ver mas">
                        @if($k==0 and !$abierta0)
                        <ion-icon name="chevron-down-outline"></ion-icon></span>
                        @else
                        <ion-icon name="chevron-up-outline"></ion-icon></span>
                        @endif
                </i>
            </div>
            @foreach($data['serv_tec_10']->where('cliente_id',$gst10->cliente_id) as $st10)
                @if($st10->equipo())
                <a href="{{ route('equipos.detail',array('id'=>$st10->equipo()->id)) }}?show=rows&tab=3"
                    class="chip  chip-media ml-05 mb-05 gst10list gst10{{$gst10->cliente_id}}"  style="width:98%; @if($k!=0 or $abierta0)  display:none; @endif">
                    <i class="chip-icon bg-{!!getStatusBgColor($st10->estatus)!!}">
                        {{$st10->estatus}}
                    </i>
                    <span class="chip-label">{{$st10->equipo()->numero_parte}}</span>
                </a>
                @endif
            @endforeach
        @endforeach
    @endif
    </div>
</div>  <br/>
<script>
    $('.abrirgst10').click(function(){
      console.log('abrir');
        var id=$(this).attr('id');
        var clase='.'+id;
        var flechagst10='.flecha'+id;
        console.log(flechagst10);
        $('.gst10list').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        $('.flechagst10').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flechagst10).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');

    });
</script>