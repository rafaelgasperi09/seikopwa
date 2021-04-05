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
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('message.error') }}
            </div>
        @endif
        @if (Session::has('message.success'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ nl2br(Session::get('message.success')) }}
            </div>
        @endif
    </div>
</div>
