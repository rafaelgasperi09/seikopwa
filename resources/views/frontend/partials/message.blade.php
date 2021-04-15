<!-- messages toast  -->
<div class="row">
    <div class="col-md-12">
        @if (count($errors->all()) > 0)
            <!-- toast top iconed -->
            <div id="toast-0" class="toast-box toast-top">
                <div class="in">
                    <ion-icon name="checkmark-circle" class="text-danger"></ion-icon>
                    <div class="text-danger">
                        <ul>
                            @foreach($errors->all() as $e)
                                <li/>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-text-danger close-button">CLOSE</button>
            </div>
            <script>toastbox('toast-0', 14000)</script>
            <!-- * toast top iconed -->
        @endif
        @if (Session::has('message.error'))
            <!-- toast top iconed -->
            <div id="toast-1" class="toast-box toast-top">
                <div class="in">
                    <ion-icon name="checkmark-circle" class="text-danger"></ion-icon>
                    <div class="text-danger">
                        {{ Session::get('message.error') }}
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-text-danger close-button">CLOSE</button>
            </div>
            <script>toastbox('toast-1', 14000)</script>
            <!-- * toast top iconed -->
        @endif
        @if (Session::has('message.success'))
            <div id="toast-2" class="toast-box toast-top">
                <div class="in">
                    <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
                    <div class="text-success">
                        {{ Session::get('message.success') }}
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-text-success close-button">CLOSE</button>
            </div>
            <script>toastbox('toast-2', 10000)</script>
        @endif
    </div>
</div>
<!-- end messages toast  -->
