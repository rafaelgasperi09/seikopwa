@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Usuario','subtitle'=>'Editar rol'))
    <div class="section full mt-2 mb-2">
        <div class="wide-block pb-1 pt-2">
            {{ Form::model($data, array('route' => array('role.update', $data->id), 'method' => 'PUT' , 'role' => 'form','class'=>'form-horizontal')) }}
            <div class="section mt-2 mb-5">
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="email">Nombre</label>
                        {{ Form::text('long_name',$data->long_name,array('class'=>'form-control','placeholder'=>'Nombre','required')) }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                    <div class="input-wrapper">
                        <label class="label" for="email">Tipo</label>
                        {{ Form::text('tipo',$data->tipo,array('class'=>'form-control','disabled')) }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                @foreach(array_chunk(Config::get('permisos.permissions'),2,true) as $block)
                    <div class="row">
                        @foreach($block as $key => $permisos)
                            @if($key != 'roles')
                            <div class="col-md-6">
                                <div style="background: #dedede;margin: 1em 0;text-align: center">
                                    <h2 style="text-align: center">Modulo {{ $key }}</h2>
                                </div>
                                @foreach($permisos as $key2 => $value)
                                <div class="m-1">
                                        <div class="col-8">{{ $value }}</div>
                                        <div class="custom-control custom-switch col-4">
                                            <input name="{{ $key2 }}" type="checkbox" class="custom-control-input" id="customSwitch_{{ $key2 }}" @if ($sentryRol->hasAccess($key2)) checked @endif>
                                            <label class="custom-control-label" for="customSwitch_{{ $key2 }}"></label>
                                        </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                                @if(current_user()->id == 1)
                                    <div class="col-md-6">
                                        <div style="background: #dedede;margin: 1em 0;text-align: center">
                                            <h2 style="text-align: center">Modulo {{ $key }}</h2>
                                        </div>
                                        @foreach($permisos as $key2 => $value)
                                            <div class="m-1">
                                                <div class="col-8">{{ $value }}</div>
                                                <div class="custom-control custom-switch col-4">
                                                    <input name="{{ $key2 }}" type="checkbox" class="custom-control-input" id="customSwitch_{{ $key2 }}" @if ($sentryRol->hasAccess($key2)) checked @endif>
                                                    <label class="custom-control-label" for="customSwitch_{{ $key2 }}"></label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                @include('frontend.partials.btnSubmit')
            </div>
            {{ Form::close() }}
        </div>
    </div>
    @include('frontend.usuarios.modal_change_password')
    @include('frontend.usuarios.modal_change_photo')
@stop
