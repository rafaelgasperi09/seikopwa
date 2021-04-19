@foreach($data as $dato)
<li class="multi-level">
    <a href="#{{$dato->name}}" class="item">
        <div class="imageWrapper">
            <img src="{{url('assets/img/settings.png')}}" alt="image" class="imaged w64">
        </div>
        <div class="in">
            <div>{{$dato->name}}</div>
        </div>
    </a>
    <!-- sub menu -->
    <ul class="listview image-listview" style="display: none;">
        <li>
            <a href="{{ route('role.edit',$dato->id) }}" class="item">
                <div class="icon-box bg-primary">
                    <ion-icon name="eye-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                </div>
                <div class="in">
                    Detalle
                </div>
            </a>
        </li>
    </ul>
    <!-- * sub menu -->
</li>
@endforeach
