{{ Form::hidden($campo_nombre,'',['id'=>$campo_nombre]) }}

<div class="modal fade signModal" id="signModal_{{$campo_nombre}}" role="dialog" aria-labelledby="signModal_{{$campo_nombre}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <iframe class="responsive-iframe" id="iframe_{{$campo_nombre}}" src="{{URL::to('/')}}/firma?campo={{$campo_nombre}}&ancho=500&alto=300"></iframe>
            </div>
        </div>
    </div>
</div>
<script>
    window.closeModal = function(){
        $('.signModal').modal('hide');
    };
    window.setImage_{{$campo_nombre}} = function(){
        
        $('#btn{{$campo_nombre}}').hide();
        $('#img_{{$campo_nombre}}').attr('src',$('#{{$campo_nombre}}').val());
        $('#img_{{$campo_nombre}}').show();
    }; 
    var vancho=window.innerWidth;
    var valto=window.innerHeight;
    function setSize_{{$campo_nombre}}(){
        if(window.innerWidth<window.innerHeight)
            valto=0.65*window.innerWidth;
        else
            valto=0.65*window.innerHeight;
        vancho=0.9*window.innerWidth;
        
        var url='{{URL::to('/')}}/firma?campo={{$campo_nombre}}&ancho='+vancho+'&alto='+valto;
        $('#iframe_{{$campo_nombre}}').attr('src',url);
    }
    setSize_{{$campo_nombre}}();
    
    $( window ).resize(function() {
        if(window.innerWidth!=vancho || valto!=window.innerHeight)
            setSize_{{$campo_nombre}}();
    });
</script>