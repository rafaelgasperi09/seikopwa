<div class="card text-white bg-light">
    <div class="card-header">
        <span>
            <ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon>
            Equipos pendientes por Daily Check<br/>
        </span>
    </div>
    <div class="card-body">
    @if(count($data['equipos_sin_daily_check_hoy']))
        @foreach($data['equipos_sin_daily_check_hoy'] as $id=>$equipo)
            <a href="@if(Sentinel::getUser()->hasAccess('equipos.create_daily_check'))
                {{ route('equipos.create_daily_check',array('id'=>$id)) }}
            @else
                {{ route('equipos.detail',array('id'=>$id)) }}?show=rows&tab=1
            @endif" class="chip chip-warning chip-media ml-05 mb-05" >
                <span class="chip-label">{{$equipo}}</span>
            </a>
        @endforeach

    @endif
    </div>
</div>  <br/>