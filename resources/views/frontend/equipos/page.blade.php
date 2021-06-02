@foreach($data as $e)
    <li class="multi-level">
        <a href="#" class="item">
            <div class="imageWrapper">
                <img src="{{ getEquipoIconBySubTipo($e->tipo_equipos_id,$e->subTipo->display_name) }}" alt="image" class="imaged w64">
            </div>
            <div class="in">
                <div>{{$e->numero_parte}}<br/> <small style="font-size: 8px">({{ $e->cliente->nombre }})</small></div>
            </div>
        </a>
        <!-- sub menu -->
        <ul class="listview image-listview" style="display: none;">
            @if(\Sentinel::hasAccess('equipos.detail'))
                <li>
                    <a href="{{route('equipos.detail',['id'=>$e->id])}}" class="item">
                        <div class="icon-box bg-primary">
                            <ion-icon name="eye-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                        </div>
                        <div class="in">
                            Detalle
                        </div>
                    </a>
                </li>
            @endif
            @if(\Sentinel::hasAccess('equipos.create_daily_check'))
                <li>
                    <a href="{{ route('equipos.create_daily_check',$e->id) }}" class="item">
                        <div class="icon-box bg-secondary">
                            <ion-icon name="list-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>Daily check</div>
                        </div>
                    </a>
                </li>
            @endif
            @if((!empty($e->tipo_equipos_id) or !empty($e->tipo_motore_id)) && \Sentinel::hasAccess('equipos.create_mant_prev'))
                <li>
                    <a href="@if($e->sub_equipos_id==1)
                        {{ route('equipos.create_mant_prev',[$e->id,$e->tipo_motore_id]) }}
                    @else
                        {{ route('equipos.create_mant_prev',[$e->id,$e->tipo_equipos_id]) }}
                    @endif" class="item">
                        <div class="icon-box bg-info">
                            <ion-icon name="hammer-outline" role="img" class="md hydrated" aria-label="hammer-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>Mantenimiento preventivo</div>
                        </div>
                    </a>
                </li>
            @endif
            @if(\Sentinel::hasAccess('equipos.create_tecnical_support'))
                <li>
                    <a href="{{ route('equipos.create_tecnical_support',$e->id) }}" class="item">
                        <div class="icon-box bg-warning">
                            <ion-icon name="alert-circle-outline" role="img" class="md hydrated" aria-label="alert-circle-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>Informe de servicio tecnico</div>
                        </div>
                    </a>
                </li>
            @endif
        </ul>
        <!-- * sub menu -->
    </li>
@endforeach
