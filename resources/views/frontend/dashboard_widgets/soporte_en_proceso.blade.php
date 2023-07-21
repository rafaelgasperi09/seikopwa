<div class="card text-white bg-light">
    <div class="card-header">
        <span ><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte técnico<br/>
        <span class="card-title" id="tot_equipos">{{$totstpr}} </span>En proceso</span>
    </div>
    <div class="card-body">
    @if(count($data['serv_tec_pr']))
        @foreach($data['serv_tec_pr'] as $stpr)
            @if($stpr->equipo())
                <a href="{{ route('equipos.show_tecnical_support',$stpr->id) }}"  class="chip chip-media ml-05 mb-05" style="padding:18px;width:100%">
                    
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
    @endif
    </div>
</div> <br/>