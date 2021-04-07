<<<<<<< HEAD
@foreach($data as $e)
=======
            @foreach($data as $e)
>>>>>>> 76531fb6b25167eeebbc3f2b5b0e1f41c6b6b324
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
                            <a href="#" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="eye-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Detalle
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="item">
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