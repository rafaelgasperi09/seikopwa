<div class="card text-white bg-light">
    <div class="card-header">
        <span ><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte técnico<br/>
        <span class="card-title" id="tot_equipos">{{$totstpr}} </span>En proceso</span>
    </div>
    <div class="card-body text-right">
        <div class="row" id="soporte_tecnico_pr_cli">
            <div class="spinner-border text-primary text-center" role="status">
                <span class="sr-only">Loading...</span>
            </div>   
        </div>
    
    </div>
</div> <br/>
<script>
    $(document).on('click','.abrirstpr',function(){
        var id=$(this).attr('id');
        var clase='.'+id;
        var flechastpr='.flecha'+id;
        console.log(flechastpr);
        $('.stprlist').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        $('.flechastpr').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flechastpr).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');

    });
</script>
<script>
     $(document).on('click','.abrirstpr_i',function(){
        console.log('inoperativo');
        var id=$(this).attr('id');
        var clase='.'+id;
        var flechastpr_i='.flechai_'+id;
        console.log(clase+' '+id);
        $('.stprlist_i').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        $('.flechai_stpr').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flechastpr_i).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');

    });

    setTimeout(function(){
        $.ajax({
            url: '{{secure_url('data_inicio')}}',
            dataType: "html",
            data: "tag=servicio_tecnico_proceso_cliente&user_id={{current_user()->id}}",
            type: 'get',
            success: function(data) {
                $('#soporte_tecnico_pr_cli').html(data);

            }
        });
    },1200);
</script>