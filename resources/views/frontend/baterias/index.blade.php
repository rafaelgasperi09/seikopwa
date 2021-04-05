@extends('frontend.main-layout')
@section('content')
    @include('frontend.partials.title','Baterias')
    @include('frontend.partials.search')
    <div class="section full mt mb">
        <ul class="listview image-listview media mb-2">
            @foreach($data as $dato)
                <li class="multi-level">
                    <a href="#" class="item">
                        <div class="imageWrapper">
                            <img src="{{url('assets/img/mc2.png')}}" alt="image" class="imaged w64">
                        </div>
                        <div class="in">
                            <div>{{$e->id_componente}}</div>
                        </div>
                    </a>
                    <!-- sub menu -->
                    <ul class="listview image-listview" style="display: none;">
                        <li>
                            <a href="#" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="create-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Photos
                                    <span class="badge badge-danger">10</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="item">
                                <div class="icon-box bg-secondary">
                                    <ion-icon name="videocam-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                                </div>
                                <div class="in">
                                    <div>Videos</div>
                                    <span class="text-muted">None</span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="item">
                                <div class="icon-box bg-danger">
                                    <ion-icon name="musical-notes-outline" role="img" class="md hydrated" aria-label="musical notes outline"></ion-icon>
                                </div>
                                <div class="in">
                                    <div>Music</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- * sub menu -->
                </li>
            @endforeach
        </ul>
    </div>
@stop
