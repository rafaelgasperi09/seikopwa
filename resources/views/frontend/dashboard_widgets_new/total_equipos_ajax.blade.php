
<div class="card text-white bg-light">
        <div class="card-header"><span id="tot_title">Total Equipos </span><h5 class="card-title" id="tot_equipos">0</h5></div>
        <div class="card-body">

        @if(!current_user()->isCliente())
        <div class="chip chip-media ml-05 mb-05" style="width:100%">
            <div class="col-md-4"><b>Tipo</b></div>
            <div class="col-md-4 text-center"><b>Clientes</b></div>
            <div class="col-md-4 text-right"><b>GMP</b></div>
        </div>
        @endif
        
        <div id="total_equipos">
                <div class="spinner-border text-primary text-center" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
        </div>
            
        </div>
    </div>
    <script>
        $('#tot_equipos').html("{{$totales}}");
        setTimeout(function(){
            $.ajax({
                url: '/data_inicio',
                dataType: "html",
                data: "tag=total_equipo&user_id={{current_user()->id}}&tipo={{request()->tipo}}",
                type: 'get',
                success: function(data) {
                    $('#total_equipos').html(data);

                }
            });
        }, 1000);

    </script>
