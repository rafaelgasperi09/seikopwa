@foreach($data as $dato)
<li class="multi-level">
    <a href="#{{$dato->name}}" class="item">
        <div class="imageWrapper">
            <img src="{{url('assets/img/settings.png')}}" alt="image" class="imaged w64">
        </div>
        <div class="in">
            <div>{{$dato->name}} ({{ $dato->tipo }})</div>
        </div>
    </a>
    <!-- sub menu -->
    <ul class="listview image-listview" style="display: none;">
        <li>
            <a href="{{ route('role.edit',$dato->id) }}" class="item">
                <div class="icon-box bg-success">
                    <ion-icon name="create-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                </div>
                <div class="in">
                    Edit
                </div>
            </a>
        </li>
    </ul>
    <!-- * sub menu -->
</li>
@endforeach
