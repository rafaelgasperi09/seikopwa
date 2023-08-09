<div class="card text-white bg-light">
    <div class="card-header">
        <span ><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte técnico<br/>
        <span class="card-title" id="tot_equipos">{{$totstpr}} </span>En proceso</span>
    </div>
    <div class="card-body text-right">
    @if(count($data['serv_tec_pr']))
        @foreach($data['g_serv_tec_pr'] as $k=>$gstpr)
            <div class="chip chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                <span class="chip-label ">{{$gstpr->cliente()->nombre}} </span>
                <i class="chip-icon abrirstpr"  id="stpr{{$gstpr->cliente_id}}" >
                    <span class=" pull-right flechastpr flechastpr{{$gstpr->cliente_id}}"title="Ver mas">
                        @if($k==0)
                        <ion-icon name="chevron-down-outline"></ion-icon></span>
                        @else
                        <ion-icon name="chevron-up-outline"></ion-icon></span>
                        @endif
                </i>
            </div>
            @foreach($data['serv_tec_pr']->where('cliente_id',$gstpr->cliente_id) as $stpr)
                @if($stpr->equipo())
                    <a href="{{ route('equipos.show_tecnical_support',$stpr->id) }}"  
                    class="chip chip-media ml-05 mb-05 stprlist stpr{{$gstpr->cliente_id}}" style="padding:18px;width:98%; @if($k!=0)  display:none; @endif">
                        
                        <i class="chip-icon bg-{!!getStatusBgColor($stpr->estatus)!!}">
                            {{$stpr->estatus}}
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
                        <span class="chip-label">{{$stpr->equipo()->numero_parte}}
                            @if($stpr->trabajado_por<>'')
                            <ion-icon size="large" name="checkmark-sharp" role="img" class="md hydrated text-success" style="position: absolute;top: 0px;left: 99px;" aria-label="cube outline"></ion-icon>
                            @endif
                        </span>
                        
                        <div  class="fecha pull-right" >
                            <span title="Fecha de Inicio">
                                    {{transletaDate($fecha_sta,true,'')}}
                            </span><br/>
                            <span title="Tiempo transcurrido">
                                    Hace {{$transcurrido}}
                            </span>
                        </div>
                    </a>
            @endif
            @endforeach
        @endforeach
    @endif
    </div>
</div> <br/>
<script>
    $('.abrirstpr').click(function(){
      console.log('abrir');
        var id=$(this).attr('id');
        var clase='.'+id;
        var flechastpr='.flecha'+id;
        console.log(flechastpr);
        $('.stprlist').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        $('.flechastpr').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flechastpr).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');

    });
</script>