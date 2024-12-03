<div class="card text-white bg-light">
    <div class="card-header">
        <span>
            <ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon>
            Equipos pendientes por Daily Check<br/>
        </span>
    </div>
    <div class="card-body" id="epdc_body">
        <div class="spinner-border text-primary text-center" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>  <br/>

<script>

    setTimeout(function(){
        $.ajax({
            url: '/data_inicio',
            dataType: "html",
            data: "tag=equipos_pendientes_daily_check&user_id={{current_user()->id}}&tipo={{request()->tipo}}",
            type: 'get',
            success: function(data) {
                $('#epdc_body').html(data);

            }
        });
    }, 1000);

</script>