<div class="card text-white bg-light">
    <div class="card-header">
        <span ><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte técnico<br/>
        <span class="card-title" id="tot_equipos">{{$totstp}} </span>Pendientes por asignar técnico</span>
    </div>
    <div class="card-body text-right" id="soporte_pend_tecnico">
        <div class="spinner-border text-primary text-center" role="status">
            <span class="sr-only">Loading...</span>
        </div>   
    </div>
    
</div>    
<br/>
<script>

    $(document).on('click','.abrirgst',function(){
    
        var id=$(this).attr('id');
        var clase='.'+id;
        var flechagst='.flecha'+id;
        $('.gstlist').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        $('.flechagst').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flechagst).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');

    });

    setTimeout(function(){
        $.ajax({
            url: '{{secure_url('data_inicio')}}',
            dataType: "html",
            data: "tag=soporte_pend_tecnico&user_id={{current_user()->id}}",
            type: 'get',
            success: function(data) {
                $('#soporte_pend_tecnico').html(data);

            }
        });
    },1200);
</script>