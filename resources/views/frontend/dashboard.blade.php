@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Dashboard','subtitle'=>'Bienvenido(a) a GMPCheck'))

    <div class="section mt-2">
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
        {{--}}
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
    </div>
{{--}}
<div class="section full mt-3 mb-3">
    <div class="carousel-multiple owl-carousel owl-theme">

        <div class="item">
            <div class="card">
                <img src="assets/img/sample/photo/d1.jpg" class="card-img-top" alt="image">
                <div class="card-body pt-2">
                    <h4 class="mb-0">Progressive web app ready</h4>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card">
                <img src="assets/img/sample/photo/d2.jpg" class="card-img-top" alt="image">
                <div class="card-body pt-2">
                    <h4 class="mb-0">Reusable components</h4>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card">
                <img src="assets/img/sample/photo/d3.jpg" class="card-img-top" alt="image">
                <div class="card-body pt-2">
                    <h4 class="mb-0">Great for phones & tablets</h4>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card">
                <img src="assets/img/sample/photo/d4.jpg" class="card-img-top" alt="image">
                <div class="card-body pt-2">
                    <h4 class="mb-0">Change the styles in one file</h4>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card">
                <img src="assets/img/sample/photo/d6.jpg" class="card-img-top" alt="image">
                <div class="card-body pt-2">
                    <h4 class="mb-0">Sketch source file included</h4>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card">
                <img src="assets/img/sample/photo/d5.jpg" class="card-img-top" alt="image">
                <div class="card-body pt-2">
                    <h4 class="mb-0">Written with a code structure</h4>
                </div>
            </div>
        </div>

    </div>

</div>

<div class="section mt-3 mb-3">
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-end">
            <div>
                <h6 class="card-subtitle">Discover</h6>
                <h5 class="card-title mb-0 d-flex align-items-center justify-content-between">
                    Dark Mode
                </h5>
            </div>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input dark-mode-switch" id="darkmodeswitch">
                <label class="custom-control-label" for="darkmodeswitch"></label>
            </div>

        </div>
    </div>
</div>

<div class="section mt-3 mb-3">
    <div class="card">
        <img src="assets/img/sample/photo/wide4.jpg" class="card-img-top" alt="image">
        <div class="card-body">
            <h6 class="card-subtitle">Discover</h6>
            <h5 class="card-title">Components</h5>
            <p class="card-text">
                Reusable components designed for the mobile interface and ready to use.
            </p>
            <a href="app-components.html" class="btn btn-primary">
                <ion-icon name="cube-outline"></ion-icon>
                Preview
            </a>
        </div>
    </div>
</div>

<div class="section mt-3 mb-3">
    <div class="card">
        <img src="assets/img/sample/photo/wide2.jpg" class="card-img-top" alt="image">
        <div class="card-body">
            <h6 class="card-subtitle">Discover</h6>
            <h5 class="card-title">Pages</h5>
            <p class="card-text">
                Mobilekit comes with basic pages you may need and use in your projects easily.
            </p>
            <a href="app-pages.html" class="btn btn-primary">
                <ion-icon name="layers-outline"></ion-icon>
                Preview
            </a>
        </div>
    </div>
</div>
{{--}}
@stop
