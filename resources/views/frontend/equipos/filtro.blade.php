<div id="filtro" class="collapse multi-collapse @if($filtro) in @endif" >
    {{Form::open(array("method" => "GET","action" => "EquiposController@reportes_list","role" => "form",'class'=>'form-horizontal form-prevent-multiple-submit','autocomplete'=>'off'))}}
    <div class="section full">
      
        <div class="wide-block ">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Tipo</label>
                            {{ Form::select('tipo',tipo_form(),request('tipo'), array("class" => "form-control","id"=>"type"))  }}
                           
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Equipo</label>
                            {{ Form::select('equipo_id',\App\Equipo::orderBy('numero_parte')->get()->pluck('numero_parte','id')->prepend('Seleccione'),request('equipo_id'), array("class" => "form-control","id"=>"type"))  }}
                           
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Cliente</label>
                            {{ Form::select('equipo_id',\App\Cliente::orderBy('nombre')->get()->pluck('nombre','id')->prepend('Seleccione'),request('equipo_id'), array("class" => "form-control","id"=>"type"))  }}
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Estado</label>
                            {{ Form::select('equipo_id',[''=>'Seleccione','C'=>'Cerrado','A'=>'Abierto','P'=>'Pendiente','PR'=>'Proceso'],request('equipo_id'), array("class" => "form-control","id"=>"type"))  }}
                           
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-12 text-left">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Fecha desde</label>
                            {{ Form::date('desde',null,request('desde'), array("class" => "form-control ","id"=>"desde"))  }}
                           
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-12 text-left">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Hasta</label>
                            {{ Form::date('hasta',null,request('hasta'), array("class" => "form-control","id"=>"hasta"))  }}
                           
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12 text-left">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Registrado por</label>
                            {{ Form::select('equipo_id',\App\User::orderBy('first_name')->get()->pluck('full_name','id')->prepend('Seleccione'),request('equipo_id'), array("class" => "form-control","id"=>"type"))  }}
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                           
                            <a href="{{ route('equipos.reportes_list') }}" type="button" class="btn btn-custom btn-danger  button-prevent-multiple-submit">
                                <i class="fa fa-eraser"></i>Limpiar
                            </a>
                            <button type="submit" class="btn btn-custom btn-primary button-prevent-multiple-submit">
                                <i class="fa fa-search"></i>Filtrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
      
    {{ Form::close() }}
    </div>
</div>
