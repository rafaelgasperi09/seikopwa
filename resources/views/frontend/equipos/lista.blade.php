@extends('frontend.main-layout')
@section('content')

@if(isset($equipos))
    @include('frontend.partials.title',array('title'=>'Equipos','subtitle'=>'Equipos > '.$datos["subName"].'> '.$datos["tipoName"],'route_back'=>route('equipos.index')))
    @include('frontend.partials.search_equipos',array('title'=>'Buscar por número de parte o cliente del equipo','search_url'=>'/equipos/search/'.$datos['sub'].'/'.$datos['tipo']))
    @include('frontend.partials.list',array('data'=>$equipos,'page_view'=>'equipos.page','page_url'=>'/equipos/search/'.$datos['sub'].'/'.$datos['tipo'],'search_url'=>'/equipos/search'))
@endif
@stop
