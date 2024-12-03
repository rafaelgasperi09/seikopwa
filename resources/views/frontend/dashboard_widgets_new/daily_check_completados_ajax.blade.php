<div class="card text-white bg-light">
    <div class="card-header">
        <span ><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Equipos con Daily Check<br/>
        <span class="card-title" id="tot_daily_check_completados">{{$totstpr}} </span>Completados /
         <span class="card-title" id="tot_daily_check_no_completados">{{$totstpr}} </span>Sin Completar</span>
    </div>
    <div class="card-body text-right">
        <div class="row" id="daily_check_completados">
            <!-------------------------------->
            
        
            <!-------------------------------->


            <div class="spinner-border text-primary text-center" role="status">
                <span class="sr-only">Loading...</span>
            </div>   
        </div>
    
    </div>
</div> <br/>
<script>
      $(document).on('click','.abrirecd',function(){
      console.log('abrir');
        var id=$(this).attr('id');
        var clase='.'+id;
        var flechagedc='.flecha'+id;
        console.log(clase);
        console.log(flechagedc);
        $('.ecdlist').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        $('.flechaecd').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flechagedc).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');

    });
      $(document).on('click','.abriresd',function(){
      console.log('abrir');
        var id=$(this).attr('id');
        var clase='.'+id;
        var flechagedc='.flecha'+id;
        console.log(clase);
        console.log(flechagedc);
        $('.esdlist').each(function(){
            $(this).fadeOut();
        });
        $(clase).each(function(){
            $(this).fadeIn();
        });
        $('.flechaesd').each(function(){
            $(this).html(' <ion-icon name="chevron-up-outline"></ion-icon></span>');
        });
        $(flechagedc).html(' <ion-icon name="chevron-down-outline"></ion-icon></span>');

    });

    setTimeout(function(){
        $.ajax({
            url: '/data_inicio',
            dataType: "html",
            data: "tag=daily_check_completados&user_id={{current_user()->id}}&tipo={{request()->tipo}}",
            type: 'get',
            success: function(data) {
                $('#daily_check_completados').html(data);

            }
        });
    },1500);
</script>
