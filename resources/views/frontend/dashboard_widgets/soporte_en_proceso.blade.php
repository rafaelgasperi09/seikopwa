<div class="card text-white bg-light">
    <div class="card-header">
        <span ><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte técnico<br/>
        <span class="card-title" id="tot_equipos">{{$totstpr}} </span>En proceso</span>
    </div>
    <div class="card-body text-right">
        <div class="row">
            <div class="col-6">
                <h3 class="text-success text-left">OPERATIVOS</h3>
                @if(count($data['g_serv_tec_pr_o']))
                    @foreach($data['g_serv_tec_pr_o'] as $k=>$gstpro)
                        <div class="chip chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                            <span class="chip-label ">{{$gstpro->cliente()->nombre}} </span>
                            <i class="chip-icon abrirstpr"  id="stpr{{$gstpro->cliente_id}}" >
                                <span class=" pull-right flechastpr flechastpr{{$gstpro->cliente_id}}"title="Ver mas">
                                    @if($k==0 and !$abierta0)
                                    <ion-icon name="chevron-down-outline"></ion-icon></span>
                                    @else
                                    <ion-icon name="chevron-up-outline"></ion-icon></span>
                                    @endif
                            </i>
                        </div>
                        @foreach($data['serv_tec_pr']->where('cliente_id',$gstpro->cliente_id)->where('equipo_status','O') as $stpr)
                            @if($stpr->equipo())
                                <a href="{{ route('equipos.detail',array('id'=>$stpr->equipo_id)) }}?show=rows&tab=3"  
                                class="chip chip-media ml-05 mb-05 stprlist stpr{{$gstpro->cliente_id}}" style="padding:18px;width:98%; @if($k!=0 or $abierta0)  display:none; @endif">
                                    
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
            <div class="col-6">
                <h3 class="text-danger text-left">INOPERATIVOS</h3>
                @if(count($data['g_serv_tec_pr_i']))
                    @foreach($data['g_serv_tec_pr_i'] as $l=>$gstpri) 
                        <div class="chip chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                            <span class="chip-label ">{{$gstpri->cliente()->nombre}} </span>
                            <i class="chip-icon abrirstpr_i"  id="stpri{{$gstpri->cliente_id}}" >
                                <span class=" pull-right flechai_stpr flechai_stpr{{$gstpri->cliente_id}}"title="Ver mas">
                                    @if($l==0)
                                    <ion-icon name="chevron-down-outline"></ion-icon></span>
                                    @else
                                    <ion-icon name="chevron-up-outline"></ion-icon></span>
                                    @endif
                            </i>
                        </div>
                        @foreach($data['serv_tec_pr']->where('cliente_id',$gstpri->cliente_id)->where('equipo_status','I') as $stpri)
                            @if($stpri->equipo())
                                <a href="{{ route('equipos.detail',array('id'=>$stpr->equipo_id)) }}?show=rows&tab=3"  
                                class="chip chip-media ml-05 mb-05 stprlist_i stpri{{$gstpri->cliente_id}}" style="padding:18px;width:98%; @if($l!=0)  display:none; @endif">
                                    
                                    <i class="chip-icon bg-{!!getStatusBgColor($stpri->estatus)!!}">
                                        {{$stpri->estatus}}
                                    </i>
                                    @php
                                        $fecha_sta=$stpri->estatusHistory()->orderBy('created_at','desc')->first()->created_at;
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
                                    <span class="chip-label">{{$stpri->equipo()->numero_parte}}
                                        @if($stpri->trabajado_por<>'')
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
        </div>
    
    </div>
</div> <br/>
<script>
    $('.abrirstpr').click(function(){
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
<script>
    $('.abrirstpr_i').click(function(){
        console.log('inoperativo');
        var id=$(this).attr('id');
        var clase='.'+id;
        var flechastpr_i='.flechai_'+id;
        console.log(clase+' '+id);
        $('.stprlist_i').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        $('.flechai_stpr').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flechastpr_i).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');

    });
</script>