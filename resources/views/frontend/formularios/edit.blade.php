@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Formulario','subtitle'=>'Editar'))
    <div class="section full mt-2 mb-2">
        <div class="wide-block pb-1 pt-2">
            {{Form::open(array("method" => "POST","action" => "FormulariosController@store","role" => "form",'class'=>'form-horizontal','autocomplete'=>'off'))}}
                @php $campos=['nombre','nombre_menu','titulo','tipo']; @endphp
                @foreach($data as $k=>$v) 
                @if(in_array($k,$campos))
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="nombre">{{strtoupper($k)}}</label>
                        {{ Form::text($k,$v,array('class'=>'form-control','placeholder'=>'','required','autocomplete'=>'off')) }}
                    </div>
                </div>
                @endif
                @endforeach
                
            <div class="modal-footer">
                @include('frontend.partials.btnSubmit')
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop
