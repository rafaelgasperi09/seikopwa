<div class="row">
    <div class="col-md-12">
        @if (count($errors->all()) > 0)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <ul>
                    @foreach($errors->all() as $e)
                        <li/>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (Session::has('message.error'))
            <!-- toast top iconed -->
            <div id="toast-1" class="toast-box toast-bottom">
                <div class="in">
                    <ion-icon name="checkmark-circle" class="text-danger"></ion-icon>
                    <div class="text">
                        {{ Session::get('message.error') }}
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-text-danger close-button">CLOSE</button>
            </div>
            <script>toastbox('toast-1', 4000)</script>
            <!-- * toast top iconed -->
        @endif
        @if (Session::has('message.success'))
            <div id="toast-2" class="toast-box toast-bottom">
                <div class="in">
                    <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
                    <div class="text">
                        {{ Session::get('message.success') }}
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-text-success close-button">CLOSE</button>
            </div>
            <script>toastbox('toast-2', 2000)</script>
        @endif
    </div>
</div>

