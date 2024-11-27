<div class="card text-white bg-light">
    <div class="card-header">
        <span >
            <ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon>
            Últimos reportes de servicio técnico cerrados<br/>
        </span>
    </div>
    <div class="card-body text-right" id="servicio_tecnico_cerrado">
        <div class="row" id="soporte_en_proceso">
            <div class="spinner-border text-primary text-center" role="status">
                <span class="sr-only">Loading...</span>
            </div>   
        </div>
    </div>
</div>  <br/>
<script>

    $(document).on('click','.abrirgst10',function(){
      console.log('abrir');
        var id=$(this).attr('id');
        var clase='.'+id;
        var flechagst10='.flecha'+id;
        console.log(flechagst10);
        $('.gst10list').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        $('.flechagst10').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flechagst10).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');

    });

    setTimeout(function(){
        $.ajax({
            url: '/data_inicio',
            dataType: "html",
            data: "tag=servicio_tecnico_cerrado&user_id={{current_user()->id}}",
            type: 'get',
            success: function(data) {
                $('#servicio_tecnico_cerrado').html(data);

            }
        });
    },1200);
</script>