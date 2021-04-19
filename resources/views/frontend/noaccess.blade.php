<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>GMP Check</title>
    <meta name="description" content="Mobilekit HTML Mobile UI Kit">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="{{ url('assets/img/favicon.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('assets/img/icon/192x192.png') }}">
    @yield('css')
    <link rel="stylesheet" href="{{ url('assets/css/style.css') }}">
    @laravelPWA
    <!-- Jquery -->
    <script src="{{ url('assets/js/lib/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ url('assets/js/toast.js') }}"></script>

</head>

<body>

<!-- loader -->
<div id="loader">
    <div class="spinner-border text-primary" role="status"></div>
</div>
<!-- * loader -->

<!-- App Capsule -->
<div id="appCapsule">

    <div class="error-page">
        <div class="icon-box text-danger">
            <ion-icon name="alert-circle"></ion-icon>
        </div>
        <h1 class="title">Acceso Denegado</h1>
        <div class="text mb-5">
            Su usuario no tiene permiso para realizar esta accion.
        </div>
        <div class="fixed-footer">
            <div class="row">
                <div class="col-6">
                    <a href="{{ url('/') }}" class="btn btn-secondary btn-lg btn-block">Inicio</a>
                </div>
                <div class="col-6">
                    <a href="{{ URL::previous() }}" class="btn btn-primary btn-lg btn-block">Volver a Intentar</a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- * App Capsule -->


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


</body>

</html>
