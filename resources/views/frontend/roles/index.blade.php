@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Roles','subtitle'=>'Rol > Lista','route_back'=>route('dashboard')))
    @include('frontend.partials.search',array('title'=>'Escribir nombre del rol','search_url'=>'/roles/search'))
    @include('frontend.partials.list',array('data'=>$data,'page_view'=>'roles.page','page_url'=>'','search_url'=>'/roles/search'))
@stop
