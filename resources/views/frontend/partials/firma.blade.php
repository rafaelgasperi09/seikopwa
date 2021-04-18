{{ Form::hidden('firma_base','',['id'=>'firma_base']) }}
{{ Form::hidden($campo_nombre,'',['id'=>$campo_nombre]) }}

<div class="modal fade signModal" id="signModal" role="dialog" aria-labelledby="signModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <iframe class="responsive-iframe" id="iframe" src="{{URL::to('/')}}/firma"></iframe>
            </div>
        </div>
    </div>
</div>
<script>
    var campo_firma='';

    $(document).ready(function () {

        $('.signRequest').on('click', function(event){
            campo_firma=$(this).attr('data-field');           
        });

        window.closeModal = function(){
            $('.signModal').modal('hide');
        };
        window.setImage = function(){
            
            $('#btn'+campo_firma).hide();
            $('#'+campo_firma).val($('#firma_base').val());
            $('#img_'+campo_firma).attr('src',$('#firma_base').val());
            $('#img_'+campo_firma).show();
        }; 
        var vancho=window.innerWidth;
        var valto=window.innerHeight;            
    });

</script>