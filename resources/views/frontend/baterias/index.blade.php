@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Baterias','subtitle'=>'Bateria > Lista','route_back'=>route('dashboard')))
    @include('frontend.partials.search',array('title'=>'Escribir codigo de bateria','search_url'=>'/baterias/search'))
    @include('frontend.partials.list',array('data'=>$data,'page_view'=>'baterias.page','page_url'=>'/baterias/page','search_url'=>'/baterias/search'))
@stop
