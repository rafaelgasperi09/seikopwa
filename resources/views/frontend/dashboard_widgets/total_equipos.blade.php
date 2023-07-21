<div class=" mb-2 col-md-6 col @if(current_user()->isOnGroup('tecnico')) d-none @endif">
    <div class="card text-white bg-light">
        <div class="card-header"><span id="tot_title">Total Equipos </span><h5 class="card-title" id="tot_equipos">0</h5></div>
        <div class="card-body">

        @if(!current_user()->isCliente())
        <div class="chip chip-media ml-05 mb-05" style="width:100%">
            <div class="col-md-4"><b>Tipo</b></div>
            <div class="col-md-4 text-center"><b>Clientes</b></div>
            <div class="col-md-4 text-right"><b>GMP</b></div>
        </div>
        @endif
        @foreach($data['total_equipos'] as $sub_equipos)
            @foreach($sub_equipos['tipos'] as $tipo)
                @php $totales+=$tipo['total'];  @endphp
                <a href="{{ route('equipos.tipo',array($sub_equipos['name'],$tipo['tipo_id'])) }}"
                class="chip chip-media ml-05 mb-05" style="width:100%">
                    @if(current_user()->isCliente())
                        <i class="chip-icon bg-primary">
                            {{ $tipo['total'] }}
                        </i>
                    @else
                        <i class="chip-center chip-icon bg-primary">
                            {{ $tipo['total'] }}
                        </i>
                        <i class="chip-icon bg-warning" title="GMP">
                            {{ $tipo['GMtotal'] }}
                        </i>
                    @endif
                    <span class="chip-label">{{ $tipo['tipo'] }}</span>
                </a>
            @endforeach
        @endforeach
        <?PHP /****  LISTADO DE BATERIAS  ************/?>
        @if(\Sentinel::hasAccess('baterias.index'))
        <a href="{{ route('baterias.index') }}"
            class="chip chip-media ml-05 mb-05" style="width:100%">
                <i class="chip-icon bg-success">
                    {{ $data['total_baterias'] }}
                </i>
                <span class="chip-label">Baterías</span>
            </a>
            @php
                $totales+=$data['total_baterias'];
                $totales_title='Total Equipos + Baterías';
            @endphp
        @endif
        </div>
    </div>
    <script>
        $('#tot_equipos').html("{{$totales}}");
    </script>
</div>