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
    <link rel="stylesheet" href="{{ url('assets/css/style.css') }}?t=1904">
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
        <a href="javascript:;" class="headerButton toggle-searchbox">
            <ion-icon name="search-outline"></ion-icon>
        </a>
    </div>

</div>
<!-- * App Header -->
@endif
