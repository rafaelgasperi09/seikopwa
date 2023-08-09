<div class="card text-white bg-light">
    <div class="card-header">
        <span ><ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon> Daily Check<br/>
        <span class="card-title" id="tot_equipos">{{$totdc}} </span>Pendientes de firma</span>
    </div>
    <div class="card-body  text-right">
    @if(count($data['daily_check']))
        @foreach($data['g_daily_check'] as $gdc )
            <div class="chip chip-warning chip-media ml-05 mb-05" style="width:100%;margin-top:15px !important;font-size:16px">
                <span class="chip-label">{{$gdc->cliente()->nombre}} </span>
            </div>
            @foreach($data['daily_check'] as $dc)
                @if($dc->equipo())
                <a href="@if(Sentinel::getUser()->hasAccess('equipos.edit_daily_check')) {{ route('equipos.edit_daily_check',array('id'=>$dc->id)) }} @else {{ route('equipos.detail',array('id'=>$dc->equipo_id)) }}?show=rows#dailycheck @endif"  class="chip chip-warning chip-media ml-05 mb-05" style="width:98%">
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