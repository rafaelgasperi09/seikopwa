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
        <li>
            <a href="{{ route('baterias.registrar_entrada_salida',$dato->id) }}" class="item">
                <div class="icon-box bg-secondary">
                    <ion-icon name="list-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                </div>
                <div class="in">
                    <div>Registro De Entrada y Salida</div>
                </div>
            </a>
        </li>
    </ul>
    <!-- * sub menu -->
</li>
@endforeach
