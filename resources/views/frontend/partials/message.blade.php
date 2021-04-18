        @if (count($errors->all()) > 0)
            <!-- toast top iconed -->
            <div id="msgerror" class="toast-box toast-top">
                <div class="in">
                    <ion-icon name="close-circle-outline" class="text-danger"></ion-icon>
                    <div class="text">
                    <ul>
                        @foreach($errors->all() as $e)
                            <li/>{{ $e }}</li>
                        @endforeach
                    </ul>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-text-danger close-button">Cerrar</button>
            </div>
            <script>toastbox('msgerror',4000)</script>
            <!-- * toast top iconed -->
        @endif
        @if (Session::has('message.success'))
            <div id="msgsuccess" class="toast-box toast-top">
                <div class="in">
                    <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
                    <div class="text">
                        {{ Session::get('message.success') }}
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-text-success close-button">Cerrar</button>
            </div>
            <script>toastbox('toast-2', 2000)</script>
        @endif
