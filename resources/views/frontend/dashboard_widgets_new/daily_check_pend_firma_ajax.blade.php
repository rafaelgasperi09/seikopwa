<div class="card text-white bg-light">
    <div class="card-header">
        <span ><ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon> Daily Check<br/>
        <span class="card-title" id="tot_equipos_dcpf">{{$totdc}} </span>Pendientes de firma</span>
    </div>
    <div class="card-body  text-right" id='daily_check_pend_firma'>
        <div class="spinner-border text-primary text-center" role="status">
            <span class="sr-only">Loading...</span>
        </div>   
    </div>
</div>
<br/>
<script>
    $(document).on('click','.abrirgdc',function(){
        var id=$(this).attr('id');
        var clase='.'+id;
        var flechagdc='.flecha'+id;
        $('.gdclist').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        console.log(flechagdc);
        $('.flechagdc').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flechagdc).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');

    });
    setTimeout(function(){
        $.ajax({
            url: '/data_inicio',
            dataType: "html",
            data: "tag=daily_check_pend_firma&user_id={{current_user()->id}}&tipo={{request()->tipo}}",
            type: 'get',
            success: function(data) {
                $('#daily_check_pend_firma').html(data);

            }
        });
    },1200);
</script>