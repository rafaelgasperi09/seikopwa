@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Equipos','subtitle'=>'Crear equipo'))
    <div class="container-fluid">
        <br/>
        <div class="container-fluid">
        <br>
            {{Form::open(array("method" => "POST",'route' => array('maestros.equipos.update', $data->id),"role" => "form",'class'=>'form-horizontal'))}}
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="numero_parte">NÚMERO DE PARTE</label>
                        <input class="form-control" id="numero_parte" name="numero_parte" required="required" type="text" value="{{$data->numero_parte}}">
                    </div>
                </div>
                
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="modelo">MODELO</label>
                        <input class="form-control" id="modelo" name="modelo" required="required" type="text" value="{{$data->modelo}}">
                    </div>
                </div>
                
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="serie">SERIE</label>
                        <input class="form-control" id="serie" name="serie" required="required" type="text" value="{{$data->serie}}">
                    </div>
                </div>
                
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="marca_id">MARCA</label>
                        {{ Form::select('marca_id',\App\Marca::pluck('display_name','id')->prepend('Seleccione',''),$data->marca_id,array('class'=>'form-control','required')) }}
                    </div>
                </div>
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="cliente_id">TIPO</label>
                        {{ Form::select('sub_equipos_id',\App\SubEquipo::pluck('name','id')->prepend('Seleccione',''),$data->sub_equipos_id,array('class'=>'form-control')) }}
                    </div>
                </div>
                
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="tipo_equipos_id">TIPO DE EQUIPO</label>
                        {{ Form::select('tipo_equipos_id',\App\TipoEquipo::pluck('display_name','id')->prepend('Seleccione',''),$data->tipo_equipos_id,array('class'=>'form-control')) }}
                    </div>
                </div>
                
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="estado_id">ESTADO</label>
                        {{ Form::select('estado_id',\App\Estado::pluck('display_name','id')->prepend('Seleccione',''),$data->estado_id,array('class'=>'form-control','required')) }}
                    </div>
                </div>
                                
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="mastil">MÁSTIL</label>
                        <input class="form-control" id="mastil" name="mastil" type="text" value="{{$data->mastil}}">
                    </div>
                </div>
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="voltaje">VOLTAJE</label>
                        <input class="form-control" id="voltaje" name="voltaje" type="text" value="{{$data->voltaje}}">
                    </div>
                </div>
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="voltaje">FUNCION HIDRAULICA</label>
                        {{ Form::select('funcion_hidraulica_id',\App\FuncionHidraulica::pluck('display_name','id')->prepend('Seleccione',''),$data->funcion_hidraulica_id,array('class'=>'form-control','required')) }}
                    </div>
                </div>
                
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="capacidad_de_carga">CAPACIDAD DE CARGA</label>
                        <input class="form-control" id="capacidad_de_carga" name="capacidad_de_carga" type="text" value="{{$data->capacidad_de_carga}}">
                    </div>
                </div>                
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="garantia_activacion">GARANTÍA ACTIVACIÓN</label>
                        <input class="form-control" id="garantia_activacion" name="garantia_activacion" type="date" value="{{$data->garantia_activacion}}">
                    </div>
                </div>
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="garantia_culminacion">GARANTÍA CULMINACIÓN</label>
                        <input class="form-control" id="garantia_culminacion" name="garantia_culminacion" type="date" value="{{$data->garantia_culminacion}}">
                    </div>
                </div>
                
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="cliente_id">CLIENTE</label>
                        {{ Form::select('cliente_id',\App\Cliente::pluck('nombre','id')->prepend('Seleccione',''),$data->cliente_id,array('class'=>'form-control')) }}
                    </div>
                </div>
                
                <div class="form-group boxed col-6">
                    <div class="input-wrapper">
                        <label class="label" for="descripcion">DESCRIPCIÓN</label>
                        {{ Form::textarea('descripcion',$data->descripcion,array('class'=>'form-control','id'=>'descripcion','maxlength'=>'255','rows'=>3)) }}
                    </div>
                </div>
                
                <div class="form-group boxed col-6 text-right">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <br/><br/><br/>
                </div>
            {{ Form::close() }}
        </div>
    </div>
@stop
