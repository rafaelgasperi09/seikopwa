@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Dashboard','subtitle'=>'Bienvenido(a) a GMPCheck'))

    <div class="section mt-2">
    <div class="card text-white bg-light mb-2 col-3">
        <div class="card-header"><span id="tot_title">Total Equipos </span><h5 class="card-title" id="tot_equipos">0</h5></div>
        <div class="card-body">
        @php $totales=0;  @endphp
         @foreach($data['total_equipos'] as $sub_equipos)
            @foreach($sub_equipos['tipos'] as $tipo)        
            @php $totales+=$tipo['total'];  @endphp
            <a href="{{ route('equipos.tipo',array($sub_equipos['name'],$tipo['tipo_id'])) }}" 
            class="chip chip-media ml-05 mb-05" style="width:100%">
                <i class="chip-icon bg-primary">
                    {{ $tipo['total'] }}
                </i>
                <span class="chip-label">{{ $tipo['tipo'] }}</span>
            </a>
            @endforeach
        @endforeach            
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
        $('#tot_title').html("{{$totales_title}}");

    </script>
        {{--}}
        <div class="row">
            <div class="carousel-single owl-carousel owl-theme">
                @foreach($data['total_equipos'] as $sub_equipos)
                    @foreach($sub_equipos['tipos'] as $tipo)
                        <div class="item">
                            <div class="card text-white bg-light mb-2">
                                <div class="card-header">Total Equipos {{ $tipo['tipo'] }}</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $tipo['total'] }}</h5>
                                    <a href="{{ route('equipos.tipo',array($sub_equipos['name'],$tipo['tipo_id'])) }}"
                                       class="btn btn-primary">Ver Equipos</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
   
        @if(count($data['equipos_sin_daily_check_hoy']) > 0)
        <div class="row">
            <div class="col-12">
                <div class=" alert alert-warning mb-1" role="alert">
                    Esto(s) equipos no tienen ningun chequeo diaro el dia de hoy :
                    @foreach($data['equipos_sin_daily_check_hoy'] as $key=>$value)
                        <a href="{{ route('equipos.create_daily_check',$key) }}">{{ $value }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            @foreach($data['total_equipos'] as $sub_equipos)
                @foreach($sub_equipos['tipos'] as $tipo)
                    <div class="col-6 col-md-4">
                        <div class="card text-white bg-light mb-2">
                            <div class="card-header">Total Equipos {{ $tipo['tipo'] }}</div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $tipo['total'] }}</h5>
                                <a href="{{ route('equipos.tipo',array($sub_equipos['name'],$tipo['tipo_id'])) }}"
                                   class="btn btn-primary">Ver Equipos</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
                    <div class="col-6 col-md-4">
                        @if(\Sentinel::hasAccess('baterias.index'))
                            <div class="card text-white bg-light mb-2">
                                <div class="card-header">Total Baterias</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $data['total_baterias'] }}</h5>
                                    <a href="{{ route('baterias.index') }}" class="btn btn-primary">Ver Baterias</a>
                                </div>
                            </div>
                        @endif
                    </div>
        </div>
        {{--}}
    </div>

@stop
