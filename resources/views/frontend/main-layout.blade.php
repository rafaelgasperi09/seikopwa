@include('frontend.partials.header')
<!-- App Capsule -->
<div id="appCapsule">
    @yield('content')
</div>
<!-- * App Capsule -->
@include('frontend.partials.sidebar')
@include('frontend.partials.footer')
