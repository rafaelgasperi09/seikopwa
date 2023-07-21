
@extends('frontend.graficos-layout')
@section('content')
        <div class="row chart_content">
            <div class=" mb-2 col-md-12 col"  >
                <div class="row">
                    <div class='col-md-1'>
                    Desde<br/>
                    {{ Form::date('desde',null,request('desde'),array('class'=>'form-control','autocomplete'=>'off','id'=>'desde')) }} 
                    </div>
                    <div class='col-md-1'>
                    Hasta<br/>
                    {{ Form::date('hasta',null,request('hasta'),array('class'=>'form-control','autocomplete'=>'off','id'=>'hasta')) }} 
                    </div>
                    <div class='col-md-2'>
                     Cliente
                    {{ Form::select('cliente_id',$clientes,request('cliente_id'),array('class'=>'form-control','autocomplete'=>'off','id'=>'cliente_id')) }} 
                    </div>
                </div>

            </div>
        @for($i=1;$i<=6;$i++)
            <div class=" mb-2 col-md-4 col chart"  >
                <div id="chart{{$i}}"></div>
            </div>
            @include('frontend.dashboard.grafico_columna',['id'=>'chart'.$i,'data'=>$data['chart'.$i],'indice'=>$data['indice'][$i],'titulos'=>$data['leyenda'][$i],'title'=>$data['titulo'][$i],'ejey'=> $data['ejey'][$i]])
        @endfor

        </div>
        <script>

        </script>

   

@stop
