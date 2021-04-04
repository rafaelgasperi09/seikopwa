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


<div class="section full mt mb">
        <ul class="listview image-listview media mb-2">
            @foreach($tipos as $i)
            <li>
                <a href="#" class="item">
                    <img src="assets/img/mc.png" alt="image" class="image">
                    <div class="in">
                        <div>{{$i->display_name}}</div>
                       
                    </div>
                </a>
            </li>            

            @endforeach
         </ul> 
</div>
@stop
