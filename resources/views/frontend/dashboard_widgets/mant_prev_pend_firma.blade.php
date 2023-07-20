<div class="card text-white bg-light">
    <div class="card-header">
        <span><ion-icon  class="text-secondary" size="large" name="build-outline"></ion-icon>Mantenimiento preventivo<br/>
        <span class="card-title" id="tot_equipos">{{$totmp}} </span>Pendientes de firma</span>
    </div>
    <div class="card-body">
    @if(count($data['mant_prev']))
        @foreach($data['mant_prev'] as $mp)
        <a href="@if(Sentinel::getUser()->hasAccess('equipos.edit_mant_prev'))
            {{ route('equipos.edit_mant_prev',array('id'=>$mp->id)) }}
        @else
            {{ route('equipos.detail',array('id'=>$mp->equipo_id)) }}?show=rows&tab=2
        @endif"  class="chip chip-warning chip-media ml-05 mb-05" style="width:100%">
            <i class="chip-icon">
                Ir
            </i>
            <span class="chip-label">{{$mp->equipo()->numero_parte}} </span>
            <span class="fecha pull-right" title="Fecha de creacion">{{transletaDate($mp->created_at,true,'')}}</span>
        </a>
        @endforeach

    @endif
    </div>
</div>
<br/>