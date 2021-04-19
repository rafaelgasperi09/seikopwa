
            @foreach($data as $e)
                <li class="multi-level">
                    <a href="#" class="item">
                        <div class="imageWrapper">
                            <img src="{{url('assets/img/mc2.png')}}" alt="image" class="imaged w64">
                        </div>
                        <div class="in">
                            <div>{{$e->numero_parte}}</div>
                        </div>
                    </a>
                    <!-- sub menu -->
                    <ul class="listview image-listview" style="display: none;">
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
                        </li>
                        <li>
                            <a href="#" class="item">
                                <div class="icon-box bg-info">
                                    <ion-icon name="hammer-outline" role="img" class="md hydrated" aria-label="hammer-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    <div>Mantenimiento preventivo</div>
                                </div>
                            </a>
                        </li>
                        </li>
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
                    </ul>
                    <!-- * sub menu -->
                </li>
            @endforeach

@foreach($data as $e)
    <li class="multi-level">
        <a href="#" class="item">
            <div class="imageWrapper">
                <img src="{{url('assets/img/mc2.png')}}" alt="image" class="imaged w64">
            </div>
            <div class="in">
                <div>{{$e->numero_parte}}</div>
            </div>
        </a>
        <!-- sub menu -->
        <ul class="listview image-listview" style="display: none;">
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
            </li>
            @empty(!$e->tipo_equipos_id)
            <li>
                <a href="{{ route('equipos.create_mant_prev',[$e->id,$e->tipo_equipos_id]) }}" class="item">
                    <div class="icon-box bg-info">
                        <ion-icon name="hammer-outline" role="img" class="md hydrated" aria-label="hammer-outline"></ion-icon>
                    </div>
                    <div class="in">
                        <div>Mantenimiento preventivo</div>
                    </div>
                </a>
            </li>
            @endempty
            </li>
            <li>
                <a href="#" class="item">
                    <div class="icon-box bg-warning">
                        <ion-icon name="alert-circle-outline" role="img" class="md hydrated" aria-label="alert-circle-outline"></ion-icon>
                    </div>
                    <div class="in">
                        <div>Informe de servicio tecnico</div>
                    </div>
                </a>
            </li>
        </ul>
        <!-- * sub menu -->
    </li>
@endforeach

