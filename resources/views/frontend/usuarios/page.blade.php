@foreach($data as $dato)
    <li class="multi-level">
        <a href="#" class="item">
            <div class="imageWrapper">
                @empty($dato->photo)
                    <img src="{{url('assets/img/user.png')}}" alt="image" class="imaged w64">
                @else
                    <img src="{{ \Storage::url($dato->photo)}}" alt="image" class="imaged w64">
                @endif
            </div>
            <div class="in">
                <div>{{$dato->getFullName()}}<br/><small>{{ $dato->email }}</small> </div>
                <br/>
                <small>({{ $dato->roles()->first()->name }})</small>
            </div>
        </a>
        <!-- sub menu -->
        <ul class="listview image-listview" style="display: none;">
            <li>
                <a href="{{ route('usuarios.detail',$dato->id) }}" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="eye-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                    </div>
                    <div class="in">
                        Detalle
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ route('usuarios.profile',$dato->id) }}" class="item">
                    <div class="icon-box bg-secondary">
                        <ion-icon name="person-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                    </div>
                    <div class="in">
                        <div>Perfil</div>
                    </div>
                </a>
            </li>
            @if(\Sentinel::hasAccess('usuarios.delete') && !$dato->isOnGroup('Programador'))
            <li>
                <a href="#" class="item" data-toggle="modal" data-target="#deleteModal"
                data-action="{{ route('usuarios.delete',$dato->id) }}" data-message="Estas seguro que deseas desactivar a este usuario ?">
                    <div  class="icon-box bg-danger">
                        <ion-icon name="close-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                    </div>
                    <div class="in">
                        <div>Desactivar</div>
                    </div>
                </a>

            </li>
            @endif
        </ul>
        <!-- * sub menu -->
    </li>
@endforeach
