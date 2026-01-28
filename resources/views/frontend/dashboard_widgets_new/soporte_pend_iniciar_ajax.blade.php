<div class="card text-white bg-light">
    <div class="card-header">
        <span ><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Tickets de soporte técnico<br/>
        <span class="card-title" id="serv_tect_pend_ini">{{$totstpia}}</span title="Se muestra la lista al tecnico asignado"> Pendientes de iniciar</span>
    </div>
    <div class="card-body text-right" id="soporte_pend_iniciar">
        <div class="spinner-border text-primary text-center" role="status">
            <span class="sr-only">Loading...</span>
        </div>   
    </div>
</div>
<br/>
<script>

    $(document).on('click','.abrirsta',function(){
      console.log('abrir');
        var id=$(this).attr('id');
        var clase='.'+id;
        var flechasta='.flecha'+id;
        console.log(flechasta);
        $('.stalist').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        $('.flechasta').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flechasta).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');

    });
    setTimeout(function(){
        $.ajax({
            url: '/data_inicio',
            dataType: "html",
            data: "tag=soporte_pend_iniciar&user_id={{current_user()->id}}&tipo={{request()->tipo}}",
            type: 'get',
            success: function(data) {
                $('#soporte_pend_iniciar').html(data);

            }
        });
    },1300);
</script>