<div class="card text-white bg-light">
    <div class="card-header">
        <span>
            <ion-icon  class="text-secondary" size="large" name="calendar-outline"></ion-icon>
            Daily Check de equipos
        </span>
    </div>
    <div class="card-body">
    @if(count($data['global_sin_daily_check_hoy']) )
        <table class="table table-striped datatable responsive">
            <thead>
            <tr>
                <th scope="col">Cliente</th>
            </tr>
            </thead>
            @foreach($data['global_sin_daily_check_hoy'] as $dce)
            <tr>
                <td>
                    <a href="{{ route('equipos.detail',array('id'=>$stpr->id)) }}?show=rows&tab=1" class="chip  chip-media ml-05 mb-05"  style="width:100%;background-color:#b9d1ee">
                        <i class="chip-icon2 bg-primary">
                            {{$dce["equipos"]}}
                        </i>
                        @if($dce["daily_check"]==0)
                            <i class="chip-icon bg-danger">
                                {{$dce["daily_check"]}}
                            </i>
                        @elseif($dce["equipos"]>$dce["daily_check"])
                            <i class="chip-icon bg-warning">
                                {{$dce["daily_check"]}}
                            </i>
                        @else
                            <i class="chip-icon bg-success">
                                {{$dce["daily_check"]}}
                            </i>
                        @endif
                        <span class="chip-label">{{$dce["nombre"]}}</span>
                    </a>
                </td>
            </tr>
            @endforeach
        </table>
    @endif
    </div>
</div>  <br/>