@extends('frontend.main-layout')

@section('content')
@include('frontend.partials.title',array('title'=>'Detalle de Equipos','subtitle'=>$data->numero_parte))
<div class="section full mt-2">
<div class="accordion" id="detalle">
    <div class="item">
        <div class="accordion-header">
            <button class="btn collapsed" type="button" data-toggle="collapse" data-target="#accordion001" aria-expanded="false">
                <ion-icon name="help-circle-outline" role="img" class="md hydrated" aria-label="help circle outline"></ion-icon>
                About
            </button>
        </div>
        <div id="accordion001" class="accordion-body collapse" data-parent="#detalle" style="">
            <div class="accordion-content">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent at augue eleifend,
                lacinia
                ex quis, condimentum erat. Nullam a ipsum lorem.
            </div>
        </div>
    </div>

    <div class="item">
        <div class="accordion-header">
            <button class="btn collapsed" type="button" data-toggle="collapse" data-target="#accordion002" aria-expanded="false">
                <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="document outline"></ion-icon>
                Detail
            </button>
        </div>
        <div id="accordion002" class="accordion-body collapse" data-parent="#detalle" style="">
            <div class="accordion-content">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent at augue eleifend,
                lacinia
                ex quis, condimentum erat. Nullam a ipsum lorem.
            </div>
        </div>
    </div>

    <div class="item">
        <div class="accordion-header">
            <button class="btn collapsed" type="button" data-toggle="collapse" data-target="#accordion003" aria-expanded="false">
                <ion-icon name="chatbox-ellipses-outline" role="img" class="md hydrated" aria-label="chatbox ellipses outline"></ion-icon>
                Comment
            </button>
        </div>
        <div id="accordion003" class="accordion-body collapse" data-parent="#detalle" style="">
            <div class="accordion-content">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent at augue eleifend,
                lacinia
                ex quis, condimentum erat. Nullam a ipsum lorem.
            </div>
        </div>
    </div>

</div>    
</div>

@stop


