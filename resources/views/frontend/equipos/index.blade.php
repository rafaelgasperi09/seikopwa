@extends('frontend.main-layout')
@section('content')
@if(isset($subEquipos))
    @include('frontend.partials.title',array('subtitle'=>'Equipos > Tipos','title'=>'Equipos'))
    <div class="">
            <ul class="nav nav-tabs style1" role="tablist">
            @php
                $selected="true";$active="active";
            @endphp
            @foreach($subEquipos as $s)
                <li class="nav-item ">
                    <a class="nav-link {{$active}}" data-toggle="tab" href="#{{$s->name}}" role="tab" aria-selected="{{ $selected}}">
                        {{$s->display_name}}
                    </a>
                </li>
                @php
                    $selected="false";$active="";
                @endphp
            @endforeach
            </ul>
    </div>
    @php
        $active="show active";
    @endphp
    <div class="tab-content mt-1">

        @foreach($subEquipos as $s)

            <div class="tab-pane fade {{$active}}" id="{{$s->name}}" role="tabpanel">
                <div class="section full mt mb">
                <div class="section-title"><h3>{{$s->display_name}}</h3><br/><p>Seleccione un tipo de la lista</p></div>
                    <ul class="listview image-listview media mb-2">

                        @if(isset($tipos[$s->id]))

                            @foreach($tipos[$s->id] as $key=>$t)

                            <li>
                                <a href="{{route('equipos.tipo',['sub'=>$s->name,'id'=>$key])}}" class="item">
                                    <div class="imageWrapper">
                                        <img src="{{ getEquipoIconBySubTipo($key) }}" alt="{{ $s->name }}" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>{{$t}}</div>

                                    </div>
                                </a>
                            </li>
                            @endforeach
                            {{--}}
                            <li>
                                <a href="{{route('equipos.tipo',['sub'=>$s->name,'id'=>'todos'])}}" class="item">
                                    <img src="{{url('assets/img/mc.svg')}}" alt="image" class="image">
                                    <div class="in">
                                        <div><b>Todos</b></div>
                                    </div>
                                </a>
                            </li>
                            {{--}}
                        @endif
                    </ul>
                </div>
            </div>
            @php
                $active="";
            @endphp
        @endforeach
    </div>
@endif
@if(isset($equipos))
    @include('frontend.partials.title',array('title'=>'Equipos','subtitle'=>'Equipos > '.$datos["subName"].'> '.$datos["tipoName"]))
    @include('frontend.partials.search',array('title'=>'Escribir codigo del equipo','search_url'=>'/equipos/search/'.$datos['sub'].'/'.$datos['tipo']))
    @include('frontend.partials.list',array('data'=>$equipos,'page_view'=>'equipos.page','page_url'=>'/equipos/search/'.$datos['sub'].'/'.$datos['tipo'],'search_url'=>'/equipos/search'))
@endif
@stop
