@extends('frontend.main-layout')
@section('content')
    <div class="section full mt-2">
        <div class="section-title">How does it work?</div>
        <div class="wide-block pt-2 pb-2">
            It checks you are connected to the internet or not and warning the user via toast.
        </div>
    </div>

    <div class="section full mt-2 mb-2">
        <div class="section-title">Demo</div>
        <div class="wide-block pt-2 pb-2">
            <button type="button" class="btn btn-primary" onclick=(offlineMode())>Go Offline</button>
            <button type="button" class="btn btn-primary" onclick=(onlineMode())>Go Online</button>
        </div>
    </div>

    <div class="section full mt-2 mb-2">
        <div class="section-title">Works Automatically</div>
        <div class="wide-block pt-2 pb-2">
            Detection works automatically. The page does not need to be refreshed. When your internet connection is
            lost or reconnected, you will be notified by toast at the top of the page.
        </div>
    </div>
@stop
