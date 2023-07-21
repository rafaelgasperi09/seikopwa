<div class="card text-white bg-light">
    <div class="card-header">
        <span >
            <ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon>
            Últimos reportes de servicio técnico cerrados<br/>
        </span>
    </div>
    <div class="card-body">
    @if(count($data['serv_tec_10']) and $count=1)
        @foreach($data['serv_tec_10'] as $st10)
            @if($st10->equipo())
            <a href="{{ route('equipos.detail',array('id'=>$st10->equipo()->id)) }}?show=rows&tab=3" class="chip  chip-media ml-05 mb-05"  style="width:100%">
                <i class="chip-icon bg-{!!getStatusBgColor($st10->estatus)!!}">
                    {{$st10->estatus}}
                </i>
                <span class="chip-label">{{$st10->equipo()->numero_parte}}</span>
            </a>
            @endif
        @endforeach
    @endif
    </div>
</div>  <br/>