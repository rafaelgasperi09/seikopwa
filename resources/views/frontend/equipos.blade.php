@extends('frontend.main-layout')
@section('content')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Equipos</div>
    <div class="right">

    </div>
</div>

<div class="header-large-title">
    <h1 class="title">Equipos</h1>
</div>
<div class="wide-block pt-2 pb-2">
    <form class="search-form">
        <div class="form-group searchbox">
            <input type="text" class="form-control" value="" placeholder="Escriba el nombre del equipo">
            <i class="input-icon">
                <ion-icon name="search-outline" role="img" class="md hydrated" aria-label="search outline"></ion-icon>
            </i>
        </div>
    </form>
</div>
@if(isset($subEquipos))
    <div class="extraHeader">
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
    @foreach($subEquipos as $s)
    <div class="tab-pane fade show active" id="{{$s->name}}" role="tabpanel">
        <div class="section full mt-1">
            
        </div>
    </div>
    @endforeach
@endif
<div class="section full mt mb">
        <ul class="listview image-listview media mb-2">
            @if(isset($tipos))
                @foreach($tipos as $t)
                <li>
                    <a href="{{route('equipos.index',$t->id)}}" class="item">
                        <img src="{{url('assets/img/mc.svg')}}" alt="image" class="image">
                        <div class="in">
                            <div>{{$t->display_name}}</div>
                        
                        </div>
                    </a>
                </li>            
                @endforeach
            @elseif(isset($equipos))
                @foreach($equipos as $e)
                    <li class="multi-level">
                        <a href="#" class="item">
                            <div class="imageWrapper">
                                <img src="{{url('assets/img/mc2.png')}}" alt="image" class="imaged w64">
                            </div>
                            <div class="in">
                                <div>{{$e->numero_parte}}</div>
                            </div>
                        </a>
                        <!-- sub menu -->
                        <ul class="listview image-listview" style="display: none;">
                            <li>
                                <a href="#" class="item">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="create-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        Photos
                                        <span class="badge badge-danger">10</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="item">
                                    <div class="icon-box bg-secondary">
                                        <ion-icon name="videocam-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>Videos</div>
                                        <span class="text-muted">None</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="item">
                                    <div class="icon-box bg-danger">
                                        <ion-icon name="musical-notes-outline" role="img" class="md hydrated" aria-label="musical notes outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>Music</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <!-- * sub menu -->
                    </li>                   
                @endforeach
            @endif
         </ul> 
</div>
@stop
