@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Baterias'))
    @include('frontend.partials.search',array('title'=>'Escribir codigo de bateria'))
    @include('frontend.partials.list',array('data'=>$data,'page_view'=>'baterias.page','page_url'=>'/baterias/page'))
@stop
