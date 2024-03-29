@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title',array('title'=>'Usuario','subtitle'=>'Crear Nuevo'))
    <div class="section full mt-2 mb-2">
        <div class="wide-block pb-1 pt-2">
            {{Form::open(array("method" => "POST","action" => "UserController@store","role" => "form",'class'=>'form-horizontal','autocomplete'=>'off'))}}
            <div class="section mt-2 mb-5">
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="rol_id">Rol</label>
                        <select class="form-control" required="" id="rol" name="rol_id">
                            
                            <option selected="selected" value="">Seleccionar rol</option>
                            @foreach($roles as $r)
                            <option  data-tipo='{{$r->tipo}}' value="{{$r->id}}">{{$r->name}} ({{$r->tipo}})</option>
                            @endforeach
                        </select>
                        
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="email">Correo Electrónico (usuario para ingresar)</label>
                        {{ Form::email('email',null,array('class'=>'form-control','placeholder'=>'Correo Electrónico','required','autocomplete'=>'off')) }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="first_name">Nombre</label>
                        {{ Form::text('first_name',null,array('class'=>'form-control','placeholder'=>'Nombre','required')) }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="last_name">Apellido</label>
                        {{ Form::text('last_name',null,array('class'=>'form-control','placeholder'=>'Apellido','required')) }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                @include('frontend.partials.typeahead',array('field_label'=>'Usuario','field_name'=>'crm_user_id','items'=>$users,'valor_th'=>['',''],'display'=>'none'))
                @include('frontend.partials.typeahead',array('field_label'=>'Cliente','field_name'=>'crm_cliente_id','items'=>$clientes,'valor_th'=>['',''],'display'=>'none','small'=>'Esta selección sirve para determinar que equipos pertecen a este nuevo cliente usando la información del CRM.'))

                <div class="divider  mt-2 mb-3"></div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="password">Contraseña</label>
                        {{ Form::password('password',array('class'=>'form-control','placeholder'=>'Contraseña','required','autocomplete'=>'off')) }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <label class="label" for="password_confirmation">Confirmar Contraseña</label>
                        {{ Form::password('password_confirmation',  array("class" => "form-control",'placeholder'=>'Confirmar Contraseña','required','autocomplete'=>'off'))  }}
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                        </i>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @include('frontend.partials.btnSubmit')
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <script>
        function contiene(texto, buscado) {
            return texto.indexOf(buscado);
        }
        


         /* if($("#rol").text() == 2){
                $('#group_crm_user_id').show();
                $('#group_crm_cliente_id').hide();
            }else if($("#rol").val() == 3 || $("#rol").val() == 4){ // supervisor_operador-clinete
                $('#group_crm_user_id').hide();
                $('#group_crm_cliente_id').show();
            }else if($("#rol").val() == 5){ // operador
                $('#group_crm_user_id').show();
                $('#group_crm_cliente_id').hide();
            }else if($("#rol").val() == 6){ // tecnico_gmp
                $('#group_crm_user_id').show();
                $('#group_crm_cliente_id').show();
            }
            */
        $("#rol").change(function (){
            var selec=$('option:selected', this).attr('data-tipo');
             if(selec=='gmp'){
                $('#group_crm_cliente_id').hide();
                $('#group_crm_user_id').show();
             }

             if(selec=='cliente'){
                $('#group_crm_cliente_id').show();
                $('#group_crm_user_id').hide();
             }
            console.log(selec);
            /*if($("#rol").val() == 2){
                $('#group_crm_user_id').show();
                $('#group_crm_cliente_id').hide();
            }else if($(this).val() == 3 || $(this).val() == 4){ // supervisor_operador-clinete
                $('#group_crm_user_id').hide();
                $('#group_crm_cliente_id').show();
            }else if($(this).val() == 5){ // operador
                $('#group_crm_user_id').show();
                $('#group_crm_cliente_id').hide();
            }else if($(this).val() == 6){ // tecnico_gmp
                $('#group_crm_user_id').show();
                $('#group_crm_cliente_id').show();
            }*/
        });
    </script>
@stop
