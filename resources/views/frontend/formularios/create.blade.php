@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Formulario','subtitle'=>'Crear Nuevo'))
    <div class="section full mt-2 mb-2">
        <div class="wide-block pb-1 pt-2">
            {{Form::open(array("method" => "POST","action" => "FormulariosController@store","role" => "form",'class'=>'form-horizontal','autocomplete'=>'off'))}}
                @php $campos=['nombre','nombre_menu','titulo','tipo']; @endphp
                @foreach($campos as $c)
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="nombre">{{strtoupper($c)}}</label>
                        {{ Form::text($c,null,array('class'=>'form-control','placeholder'=>'','required','autocomplete'=>'off')) }}
                    </div>
                </div>
                @endforeach
                
            <div class="modal-footer">
                @include('frontend.partials.btnSubmit')
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop
