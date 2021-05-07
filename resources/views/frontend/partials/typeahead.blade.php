@if(!isset($value[0])) <?php $value[0]=''?> @endif
@if(!isset($value[1])) <?php $value[1]=''?> @endif
@if(!isset($display)) <?php $display='block'?> @endif
@if(!isset($placeholder)) <?php $placeholder='Buscar '.$field_label.' Nombre';?>  @endif
<div class="form-group boxed" id="group_{{ $field_name }}" style="display: {{ $display }}">
    <div class="input-wrapper">
        <label class="label" for="crm_user_id">{{ $field_label }}</label>
        {{ Form::text('typeheadfield',$value[1],array('class'=>'form-control typeahead typeheadfield','id'=>'typehead_'.$field_name,'data-field_name'=>$field_name,'data-provide'=>'typeahead','data-items'=>10,'placeholder'=>$placeholder)) }}
        {{ Form::hidden($field_name,$value[0],array('id'=>$field_name)) }}
        <i class="clear-input">
            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
        </i>
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
});
</script>
