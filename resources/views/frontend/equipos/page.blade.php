
@foreach($data as $e)
    @php 
    $ver_serv_tec=(!current_user()->isCliente() or (current_user()->isCliente() and !str_contains($e->numero_parte,'GM')));
    @endphp
    <li class="multi-level">
        <a href="#equipo_{{ $e->id }}" class="item">
            <div class="imageWrapper">
                <img src="{{ getEquipoIconBySubTipo($e->tipo_equipos_id,$e->sub_equipos_id) }}" alt="image" class="imaged w64">
            </div>
            <div class="in">
                <div>{{$e->numero_parte}}<br/> <small style="font-size: 8px">({{ $e->cliente->nombre }})</small></div>
            </div>
        </a>
        <!-- sub menu -->
        <ul class="listview image-listview" style="display: none;" id="">
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
            @if(\Sentinel::hasAnyAccess(['equipos.create_daily_check','equipos.see_daily_check']))
                <li>
                   <div class="item">
                        <div class="icon-box bg-secondary">
                            <ion-icon name="list-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>Daily check</div>
                            <div class="iconsshortchuts">
                                @if(\Sentinel::hasAccess('equipos.create_daily_check'))
                                <a href="{{ route('equipos.create_daily_check',$e->id) }}"  title="Crear">
                                    <span class="iconedbox bg-primary">
                                    <ion-icon name="create-outline"></ion-icon>
                                    </span>
                                </a>
                                @endif
                                @if(\Sentinel::hasAccess('equipos.see_daily_check'))
                                <a href="{{route('equipos.detail',['id'=>$e->id])}}?show=rows&tab=1"   title="Ver">
                                    <span class="iconedbox bg-success">
                                    <ion-icon name="eye-outline"></ion-icon>
                                    </span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @endif
            @if((!empty($e->tipo_equipos_id) or !empty($e->tipo_motore_id)))
                <li>
                    <div class="item">
                        <div class="icon-box bg-info">
                            <ion-icon name="hammer-outline" role="img" class="md hydrated" aria-label="hammer-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>Mantenimiento preventivo</div>
                            <div class="iconsshortchuts">
                                @if(\Sentinel::hasAccess('equipos.create_mant_prev'))
                                <a href="@if($e->sub_equipos_id==1)
                                            {{ route('equipos.create_mant_prev',[$e->id,$e->tipo_motore_id]) }}
                                        @else
                                            {{ route('equipos.create_mant_prev',[$e->id,$e->tipo_equipos_id]) }}
                                        @endif"  title="Crear">
                                    <span class="iconedbox bg-primary">
                                    <ion-icon name="create-outline"></ion-icon>
                                    </span>
                                </a>
                                @endif
                                <a href="{{route('equipos.detail',['id'=>$e->id])}}?show=rows&tab=2"   title="Ver">
                                    <span class="iconedbox bg-success">
                                    <ion-icon name="eye-outline"></ion-icon>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            @endif
            @if(\Sentinel::hasAnyAccess(['equipos.create_tecnical_support','equipos.assign_tecnical_support','equipos.start_tecnical_support','equipos.see_tecnical_support']) and $ver_serv_tec )
                <li>
                    <div class="item">
                        <div class="icon-box bg-warning">
                            <ion-icon name="alert-circle-outline" role="img" class="md hydrated" aria-label="alert-circle-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>Informe de servicio tecnico</div>
                            <div class="iconsshortchuts">
                                @if(\Sentinel::hasAccess('equipos.create_tecnical_support'))
                                <a href="{{ route('equipos.create_tecnical_support',$e->id) }}" title="Crear">
                                    <span class="iconedbox bg-primary">
                                    <ion-icon name="create-outline"></ion-icon>
                                    </span>
                                </a>
                                @endif
                                @if(\Sentinel::hasAnyAccess(['equipos.create_tecnical_support','equipos.assign_tecnical_support','equipos.start_tecnical_support','equipos.see_tecnical_support']))
                                <a href="{{route('equipos.detail',['id'=>$e->id])}}?show=rows&tab=3"  title="Ver">
                                    <span class="iconedbox bg-success">
                                    <ion-icon name="eye-outline"></ion-icon>
                                    </span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @endif
            @if(\Sentinel::hasAnyAccess(['equipos.show_control_entrega']))
                <li>
                    <div class="item">
                        <div class="icon-box bg-success">
                            <ion-icon name="document-text-outline" role="img" class="md hydrated" aria-label="alert-circle-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>Control de entrega de montacargas para alquiler</div>
                            <div class="iconsshortchuts">
                                @if(\Sentinel::hasAccess('equipos.create_control_entrega'))
                                <a href="{{ route('equipos.create_control_entrega',$e->id) }}" title="Crear">
                                    <span class="iconedbox bg-primary">
                                    <ion-icon name="create-outline"></ion-icon>
                                    </span>
                                </a>
                                @endif
                                @if(\Sentinel::hasAnyAccess(['equipos.show_control_entrega']))
                                <a href="{{route('equipos.detail',['id'=>$e->id])}}?show=rows&tab=3"  title="Ver">
                                    <span class="iconedbox bg-success">
                                    <ion-icon name="eye-outline"></ion-icon>
                                    </span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @endif
        </ul>
        <!-- * sub menu -->
    </li>
@endforeach
