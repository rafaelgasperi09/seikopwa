<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>{{ env('APP_NAME') }}</title>
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="description" content="Aplicacion para gestion y control de las montacargas de la empresa gmp panama.">
    <meta name="keywords" content="montacasrgas ,panama, gmp, servicio tecnico, daily check, counter fc,counter sc,counter rc,reach,pallet pe,wave,combustion" />
    <link rel="icon" type="image/png" href="{{ url('images/AppImages/android/icon-144x144.png') }}" sizes="144x144">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('images/AppImages/android/icon-192x192.png') }}">
    @yield('css')
    <link rel="stylesheet" href="{{ url('assets/css/style.css?time='.time()) }}">
    <link rel="stylesheet" href="{{ url('assets/css/graficas.css?time='.time()) }}">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    @laravelPWA
    <!-- Jquery -->
    <script src="{{ url('assets/js/lib/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ url('assets/js/toast.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    {{ Html::script('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js') }}
    <!-- fullcalendar -->
    {{ Html::script('assets/js/moment.min.js') }}
    {{ Html::script('assets/js/plugins/fullcalendar/fullcalendar.min.js') }}
    {{ Html::script('assets/js/plugins/fullcalendar/lang/es.js') }}
   
    <style>
      
      #chart {
    max-width: 650px;
    margin: 35px auto;
    }

    </style>
    <script>
    window.Promise ||
      document.write('<script src="assets/js/polyfill.min.js"><\/script>');
    window.Promise ||
      document.write('<script src="assets/js/classList.min.js"><\/script>')
    window.Promise ||
      document.write('<script src="assets/js/findindex_polyfill_mdn"><\/script>')
    </script>

 <script src="/assets/js/apexcharts.min.js" ></script>
 
  

  <script>
    // Replace Math.random() with a pseudo-random number generator to get reproducible results in e2e tests
    // Based on https://gist.github.com/blixt/f17b47c62508be59987b
    var _seed = 42;
    Math.random = function() {
      _seed = _seed * 16807 % 2147483647;
      return (_seed - 1) / 2147483646;
    };
  </script>

  
</head>

<body>

<!-- loader -->
<div id="loader">
    <div class="spinner-border text-primary" role="status"></div>
</div>
<!-- * loader -->
@if(\Sentinel::check())
<!-- App Header -->
<div class="appHeader bg-primary scrolled">
    <div class="left">
        <a href="#" class="headerButton" data-toggle="modal" data-target="#sidebarPanel">
            <ion-icon name="menu-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
        Dashboard
    </div>
    <div class="right">
        <a href="{{ route('logout') }}" class="button">
            <ion-icon name="log-out-outline"></ion-icon>
        </a>
    </div>
</div>
<!-- * App Header -->
@endif
