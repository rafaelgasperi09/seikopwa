<div class="card text-white bg-light">
    <div class="card-header">
        <div class="row">
        <div class="col-md-8">
        <span ><ion-icon name="ticket-outline" size="large" class="text-secondary"></ion-icon>Equipos con Daily Check
        </div>
        <div class="col-md-4 text-right">
        <a id="exporter" data-toggle="modal" data-target="#reporte_modal" type="button" class="btn btn-success btn-rounded btn-condensed btn-sm pull-right"  title="Exportar"> <ion-icon name="download-outline" role="img" class="md hydrated" aria-label="download outline"></ion-icon></a>
        </div>
        </div>
        <br/>
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
<div class="modal fade reporte_modal" id="reporte_modal" role="dialog" aria-labelledby="reporte_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-body row">
            
            <div class="col-md-12 col-12">
                Descargar reporte
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="name1">Fecha </label>
                        {{ Form::date('fecha_desde',date('Y-m-d'),array('class'=>'form-control','id'=>'fecha_desde')) }}
                    </div>
                </div>
            </div>
            {{---------
            <div class="col-md-6 col-12 hide">
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label" for="name1">Fecha hasta</label>
                        {{ Form::date('fecha_hasta',date('Y-m-d'),array('class'=>'form-control','id'=>'fecha_hasta')) }}
                    </div>
                </div>
            </div>
            -----}}
        </div>
        <div class="modal-footer">
            <div class="btn-inline">
                <button type="button" class="btn btn-text-secondary" data-dismiss="modal">CANCELAR</button>
                <button type="button" id="descargar_reporte" class="btn btn-text-primary">DESCARGAR</button>
            </div>
        </div>
        </div>
    </div>
</div>
<script>
      $(document).on('click','#descargar_reporte',function(){
        
        var fecha_desde=$('#fecha_desde').val();
        var fecha_hasta=$('#fecha_hasta').val();
        var url="{{route('dashboard.download_excel',array('id'=>'daily_check_completados'))}}/?fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_desde;
        window.location.href=url;
        console.log('clic'+url);
      });

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
