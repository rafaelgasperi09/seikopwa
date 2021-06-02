@include('frontend.partials.header')
<!-- App Capsule -->
<div id="appCapsule">
    @include('frontend.partials.message')
    @yield('content')
</div>
<!-- * App Capsule -->
@include('frontend.partials.delete')
@include('frontend.partials.sidebar')
@include('frontend.partials.footer')
