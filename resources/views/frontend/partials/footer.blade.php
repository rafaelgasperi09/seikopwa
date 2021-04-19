@if(\Sentinel::check())
<div class="appBottomMenu">
    @if(\Sentinel::hasAccess('equipos.index'))
    <a href="{{ route('equipos.index') }}" class="item  @if(Request::is('equipos') or Request::is('equipos/*')) active @endif">
        <div class="col">
            <ion-icon name="train-outline" role="img" class="md hydrated" aria-label="chatbubble ellipses outline"></ion-icon>
            {{--}}<span class="badge badge-danger">5</span>{{--}}
        </div>
    </a>
    @endif
    @if(\Sentinel::hasAccess('baterias.index'))
    <a href="{{ route('baterias.index') }}" class="item @if(Request::is('baterias') or Request::is('baterias/*')) active @endif">
        <div class="col">
            <ion-icon name="battery-charging-outline" role="img" class="md hydrated" aria-label="cube outline"></ion-icon>
        </div>
    </a>
    @endif
    <a href="{{ route('dashboard') }}" class="item @if(Request::is('dashboard')) active @endif">
        <div class="col">
            <ion-icon name="home-outline" role="img" class="md hydrated" aria-label="home outline"></ion-icon>
        </div>
    </a>
    @if(current_user()->isOnGroup('administradores'))
        <a href="{{ route('usuarios.index') }}" class="item @if(Request::is('usuarios') or Request::is('usuarios/*')) active @endif">
            <div class="col">
                <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="layers outline"></ion-icon>
            </div>
        </a>
    @else
        <a href="{{ route('usuarios.profile',current_user()->id) }}" class="item @if(Request::is('usuarios') or Request::is('usuarios/*')) active @endif">
            <div class="col">
                <ion-icon name="person-outline" role="img" class="md hydrated" aria-label="layers outline"></ion-icon>
            </div>
        </a>
    @endif
    <a href="javascript:;" class="item" data-toggle="modal" data-target="#sidebarPanel">
        <div class="col">
            <ion-icon name="menu-outline" role="img" class="md hydrated" aria-label="menu outline"></ion-icon>
        </div>
    </a>
</div>
{{--}}
<!-- app footer -->
<div class="appFooter">
    <img src="{{ url('images/AppImages/android/icon-192x192.png') }}" alt="icon" class="footer-logo mb-2">
    <div class="footer-title">
        Copyright © GMP Panama 2021. All Rights Reserved.
    </div>
</div>
<!-- * app footer -->

<!-- welcome notification  -->

<div id="notification-welcome" class="notification-box">
    <div class="notification-dialog android-style">
        <div class="notification-header">
            <div class="in">
                <img src="{{ url('assets/img/icon/72x72.png')}}" alt="image" class="imaged w24">
                <strong>Mobilekit</strong>
                <span>just now</span>
            </div>
            <a href="#" class="close-button">
                <ion-icon name="close"></ion-icon>
            </a>
        </div>
        <div class="notification-content">
            <div class="in">
                <h3 class="subtitle">Welcome to Mobilekit</h3>
                <div class="text">
                    Mobilekit is a PWA ready Mobile UI Kit Template.
                    Great way to start your mobile websites and pwa projects.
                </div>
            </div>
        </div>
    </div>
</div>
{{--}}
<!-- * welcome notification -->
@endif

@include('frontend.partials.message')
<!-- ///////////// Js Files ////////////////////  -->
<!-- Bootstrap-->
<script src="{{ url('assets/js/lib/popper.min.js') }}"></script>
<script src="{{ url('assets/js/lib/bootstrap.min.js') }}"></script>
<!-- Ionicons -->
<script type="module" src="https://unpkg.com/ionicons@5.2.3/dist/ionicons/ionicons.js"></script>
<!-- Owl Carousel -->
<script src="{{ url('assets/js/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
<!-- jQuery Circle Progress -->
<script src="{{ url('assets/js/plugins/jquery-circle-progress/circle-progress.min.js') }}"></script>
<!-- Base Js File -->
<script src="{{ url('assets/js/base.js') }}"></script>
<!-- ///////////// CUSTOM SCRIPTS ////////////////////  -->
@yield('post_scripts')



    @yield('post_scripts')
<script>
/*
    setTimeout(() => {
        notification('notification-welcome', 5000);
    }, 2000);
    */
</script>

</body>

</html>
