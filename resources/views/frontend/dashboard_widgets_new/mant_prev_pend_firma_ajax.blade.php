@php $abierta0=true; @endphp
<div class="card text-white bg-light">
    <div class="card-header">
        <span><ion-icon  class="text-secondary" size="large" name="build-outline"></ion-icon>Mantenimiento preventivo<br/>
        <span class="card-title" id="tot_equipos_pf">{{$totmp}} </span> Pendientes de firma</span>
    </div>
    <div class="card-body text-right" id='mant_prev_pend_firma'>
        <div class="spinner-border text-primary text-center" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>
<br/>
<script>
    $(document).on('click','.abrir',function(){
        var id=$(this).attr('id');
        console.log(id);
        var clase='.'+id;
        var flecha='.flecha'+id;
        $('.gmplist').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        $('.flechagmp').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flecha).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');
        console.log(flecha);
    });
</script>
<script>

    setTimeout(function(){
        $.ajax({
            url: '{{url('data_inicio')}}',
            dataType: "html",
            data: "tag=mant_prev_pend_firma&user_id={{current_user()->id}}",
            type: 'get',
            success: function(data) {
                $('#mant_prev_pend_firma').html(data);

            }
        });
    }, 1000);

</script>