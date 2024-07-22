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
                            {{ Form::text('typeheadfield_equipo_id',request('typeheadfield_equipo_id'),array('class'=>'form-control typeahead typeheadfield','id'=>'typehead_equipo_id','data-field_name'=>'equipo_id','data-provide'=>'typeahead','data-items'=>10,'placeholder'=>'',"autocomplete"=>"off" )) }}
                            {{ Form::hidden('equipo_id',request('equipo_id'),array('id'=>'equipo_id')) }} 
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Cliente</label>
                            {{ Form::text('typeheadfield_cliente_id',request('typeheadfield_cliente_id'),array('class'=>'form-control typeahead typeheadfield','id'=>'typehead_cliente_id','data-field_name'=>'cliente_id','data-provide'=>'typeahead','data-items'=>10,'placeholder'=>'',"autocomplete"=>"off" )) }}
                            {{ Form::hidden('cliente_id',request('cliente_id'),array('id'=>'cliente_id')) }} 
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Estado</label>
                            {{ Form::select('estado',[''=>'Seleccione','C'=>'Cerrado','A'=>'Abierto','P'=>'Pendiente','PR'=>'Proceso'],request('estado'), array("class" => "form-control","id"=>"type"))  }}
                           
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-12 text-left">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Fecha desde</label>
                            {{ Form::date('desde',request('desde'), array("class" => "form-control ","id"=>"desde"))  }}
                           
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-12 text-left">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Hasta</label>
                            {{ Form::date('hasta',request('hasta'), array("class" => "form-control","id"=>"hasta"))  }}
                           
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12 text-left">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="name1">Registrado por</label>
                            {{ Form::text('typeheadfield_created_by',request('typeheadfield_created_by'),array('class'=>'form-control typeahead typeheadfield','id'=>'typehead_created_by','data-field_name'=>'created_by','data-provide'=>'typeahead','data-items'=>10,'placeholder'=>'',"autocomplete"=>"off" )) }}
                            {{ Form::hidden('created_by',request('created_by'),array('id'=>'created_by')) }} 
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
@php
 $current_user=current_user();
 @endphp   
<script>
$(document).ready(function(){
$('#typehead_equipo_id').typeahead({
        items:20,
        source: [
            @foreach(\App\Equipo::when($current_user->isCliente(),function($q) use($current_user){
                $q->whereRaw('cliente_id in ('.$current_user->crm_clientes_id.')');
            })->pluck('numero_parte','id') as $key=>$value)
                {id: '{{ $key }}', name: '{{ trim($value) }}'},
            @endforeach
        ],
        autoSelect: true
    });

    $('#typehead_equipo_id').change(function() {

        var current = $(this).typeahead("getActive");
        console.log(' curr :'+current);
        if (current) {
            // Some item from your model is active!
            if (current.name == $(this).val()) {
                // This means the exact match is found. Use toLowerCase() if you want case insensitive match.
                $('#equipo_id').val(current.id);
            }
        }
    });


$('#typehead_cliente_id').typeahead({
        items:20,
        source: [
            @foreach(\App\Cliente::orderBy('nombre')->when($current_user->isCliente(),function($q) use($current_user){
                $q->whereRaw('id in ('.$current_user->crm_clientes_id.')');
            })->pluck('nombre','id') as $key=>$value)
                {id: '{{ $key }}', name: '{{ trim($value) }}'},
            @endforeach
        ],
        autoSelect: true
    });

    $('#typehead_cliente_id').change(function() {

        var current = $(this).typeahead("getActive");
        console.log(' curr :'+current);
        if (current) {
            // Some item from your model is active!
            if (current.name == $(this).val()) {
                // This means the exact match is found. Use toLowerCase() if you want case insensitive match.
                $('#cliente_id').val(current.id);
            }
        }
    });

$('#typehead_created_by').typeahead({
        items:20,
        source: [
            @foreach(\App\User::when($current_user->isCliente(),function($q) use($current_user){
                $q->whereRaw('(crm_clientes_id in ('.$current_user->crm_clientes_id.') or crm_clientes_id is null)');
            })->get()->pluck('full_name','id') as $key=>$value)
                {id: '{{ $key }}', name: '{{ trim($value) }}'},
            @endforeach
        ],
        autoSelect: true
    });

    $('#typehead_created_by').change(function() {

        var current = $(this).typeahead("getActive");
        console.log(' curr :'+current);
        if (current) {
            // Some item from your model is active!
            if (current.name == $(this).val()) {
                // This means the exact match is found. Use toLowerCase() if you want case insensitive match.
                $('#created_by').val(current.id);
            }
        }
    });

});

   

</script>