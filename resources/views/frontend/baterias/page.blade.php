@foreach($data as $dato)
<li class="multi-level">
    <a href="#{{$dato->id_componente}}" class="item">
        <div class="imageWrapper">
            <img src="{{url('assets/img/bateria.png')}}" alt="image" class="imaged w64">
        </div>
        <div class="in">
            <div>{{$dato->id_componente}}</div>
        </div>
    </a>
    <!-- sub menu -->
    <ul class="listview image-listview" style="display: none;">
        @if(\Sentinel::hasAccess('baterias.detail'))
        <li>
            <a href="{{ route('baterias.detail',$dato->id) }}" class="item">
                <div class="icon-box bg-primary">
                    <ion-icon name="eye-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                </div>
                <div class="in">
                    Detalle
                </div>
            </a>
        </li>
        @endif
        @if(\Sentinel::hasAccess('baterias.register_in_and_out'))
        <li>
            <a href="{{ route('baterias.register_in_and_out',$dato->id) }}" class="item">
                <div class="icon-box bg-secondary">
                    <ion-icon name="list-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                </div>
                <div class="in">
                    <div>Registro De Entrada y Salida</div>
                </div>
                
            </a>
        </li>
        <li>
            <a href="{{ route('baterias.serv_tec',$dato->id) }}" class="item">
                <div class="icon-box bg-warning">
                    <ion-icon name="alert-circle-outline" role="img" class="md hydrated" aria-label="Informe de servicio técnicoS"></ion-icon>
                </div>
                <div class="in">
                    <div>Informe de Servicio técnico</div>
                </div>
            </a>
        </li>
        @endif
    </ul>
    <!-- * sub menu -->
</li>
@endforeach
