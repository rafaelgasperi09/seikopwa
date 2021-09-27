@if(!isset($value[0])) <?php $value[0]=''?> @endif
@if(!isset($data->crm_clientes_id)) <?php $clientes_id=''?> @else $clientes_id=$data->crm_clientes_id;  @endif
@if(!isset($value[1])) <?php $value[1]=''?> @endif
@if(!isset($display)) <?php $display='block'?> @endif
@if(!isset($placeholder)) <?php $placeholder='Buscar '.$field_label.' Nombre';?>  @endif
<div class="form-group boxed" id="group_{{ $field_name }}" style="display: {{ $display }}">
    <div class="input-wrapper">
        <label class="label" for="crm_user_id">{{ $field_label }}
            <div style="float: right;display: inline-block;">
            @if($field_name=='crm_cliente_id')
            <span class="btn btn-sm btn-outline-primary rounded shadowed mr-1 mb-1" id="add_{{ $field_name }}">Agregar</span>
            @endif
            <span class="btn btn-sm btn-outline-warning rounded shadowed mr-1 mb-1" id="quitar_{{ $field_name }}">Limpiar</span></div> </label>
        {{ Form::text('typeheadfield',$value[1],array('class'=>'form-control typeahead typeheadfield','id'=>'typehead_'.$field_name,'data-field_name'=>$field_name,'data-provide'=>'typeahead','data-items'=>10,'placeholder'=>$placeholder,"autocomplete"=>"off" )) }}
        {{ Form::hidden($field_name,$value[0],array('id'=>$field_name)) }}
        <i class="clear-input">
            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
        </i>
        @if($field_name=='crm_cliente_id')
        <input type="hidden" id="crm_clientes_id" name="crm_clientes_id" value="{{$clientes_id}}">
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">NOMBRE</th>
                        <th scope="col">ACCION</th>
                    </tr>
                </thead>
                <tbody id="clientes_list">

                    @if(isset($data))
                    @foreach($listaClientes as $cliente)
                    <tr id="cid{{$cliente->id}}">
                        <td>{{$cliente->nombre}}</td>
                        <td><span clientid="{{$cliente->id}}" class="btn btn-sm btn-outline-danger rounded shadowed mr-1 mb-1 del_client">Borrar</span></td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <script>
            $('#add_{{ $field_name }}').on('click',function(){
                var thval=$('#typehead_'+fieldName).val();
                var thidval=$("#crm_cliente_id").val();
                var listaclientes=$("#crm_clientes_id").val().split(',');
                if(jQuery.inArray(thidval, listaclientes) == -1) {
                    if(thval!==''){
                        thval=thval.split('(')[0];
                        $('#typehead_'+fieldName).val('');
                        $('#'+fieldName).val('');
                        $("#clientes_list").append(' <tr id="cid'+thidval+'">\
                            <td>'+thval+'</td>\
                            <td><span clientid="'+thidval+'" class="btn btn-sm btn-outline-danger rounded shadowed mr-1 mb-1 del_client">Borrar</span></td>\
                        </tr>');
                            var ninput=$("#crm_clientes_id").val()+','+thidval;
                            $("#crm_clientes_id").val(ninput);
                    }
                }
                
                
            });
            $('.del_client').on('click',function(){
                var client_id=$(this).attr('clientid');
                var input=$("#crm_clientes_id").val().split(',');
                for( var i = 0; i < input.length; i++){        
                    if ( input[i] === client_id) { 
                        input.splice(i, 1); 
                        i--; 
                    }
                }
                           
                input=input.join(',');
                $("#cid"+client_id).remove();
                $("#crm_clientes_id").val(input)
                
            });

        </script>
        @endif
    </div>
    @if(isset($small))
    <small style="color: red;">{{ $small }}</small>
    @endif
</div>
<script>
var fieldName = '{{ $field_name }}';
$(document).ready(function(){
    var fieldName = '{{ $field_name }}';
    var api_token = '{{ current_user()->api_token }}'

    $('#typehead_'+fieldName).typeahead({
        items:20,
        source: [
            @foreach($items as $key=>$value)
                {id: '{{ $key }}', name: '{{ trim($value) }}'},
            @endforeach
        ],
        autoSelect: true
    });

    $('#typehead_'+fieldName).change(function() {

        var current = $(this).typeahead("getActive");
        console.log(' curr :'+current);
        if (current) {
            // Some item from your model is active!
            if (current.name == $(this).val()) {
                // This means the exact match is found. Use toLowerCase() if you want case insensitive match.
                $('#'+fieldName).val(current.id);
            }
        }
    });
    $( "#quitar_{{ $field_name }}" ).click(function() {
        console.log('aquiiiiiiii');
        $('#typehead_'+fieldName).val('');
        $('#'+fieldName).val('');
    });
    $( "#agregar_{{ $field_name }}" ).click(function() {
        console.log('aquiiiiiiii');
        $('#typehead_'+fieldName).val('');
        $('#'+fieldName).val('');
    });

});
</script>
