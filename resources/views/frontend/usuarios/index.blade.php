@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Usuarios','subtitle'=>'Lista'))
    @include('frontend.partials.search',array('title'=>'Escribir cnombre o apellido del usuario','search_url'=>'/usuarios/search'))
    <div class="section full mt-1">
        <div class="wide-block pt-2 pb-2">
            <a href="{{ route('usuarios.create') }}" class="btn btn-success">
                <ion-icon name="add-circle-outline" class="md hydrated" aria-label="logo android"></ion-icon>
                Nuevo
            </a>
            <a href="{{ route('usuarios.import') }}" class="btn btn-warning" onclick="$('#ios-add-to-home-screen').modal();">
                <ion-icon name="cloud-download-outline" role="img" class="md hydrated" aria-label="logo apple"></ion-icon>
                Importar desde crm
            </a>
        </div>
    </div>
    @include('frontend.partials.list',array('data'=>$data,'page_view'=>'usuarios.page','page_url'=>'/usuarios/page','search_url'=>'/ususarios/search'))
@stop
