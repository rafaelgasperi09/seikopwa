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
    <link rel="icon" type="image/png" href="c" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(url('assets/img/icon/192x192.png')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('assets/css/style.css')); ?>">
    <link rel="manifest" href="<?php echo e(url('front/__manifest.json')); ?>">
</head>

<body class="bg-white">

    <!-- loader -->
    <div id="loader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- * loader -->


    <!-- App Capsule -->
    <div id="appCapsule" class="pt-0">

        <div class="login-form mt-1">
            <div class="section">
                <img src="<?php echo e(url('images/AppImages/android/icon-144x144.png?t=123')); ?>" alt="image" class="form-image">
            </div>
            <div class="section mt-1">
                <h1>Check</h1>
                <h4>Coloque sus datos para ingresar</h4>
            </div>
            <div class="section mt-1 mb-5">
                <form action="app-pages.html">
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="email" class="form-control" id="email1" placeholder="Email">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="password" class="form-control" id="password1" placeholder="Password">
                            <i class="clear-input">
                                <ion-icon name="close-circle"></ion-icon>
                            </i>
                        </div>
                    </div>


                    <div class="form-button-group">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Log in</button><br/>
                        <a href="<?php echo e(url('dashboard')); ?>" class="btn btn-primary btn-block btn-lg">Ver Dashboard</a>
                    </div>

                </form>
            </div>
        </div>


    </div>
    <!-- * App Capsule -->


    <script src="<?php echo e(url('serviceworker.js')); ?>"></script>
    <!-- ///////////// Js Files ////////////////////  -->
    <!-- Jquery -->
    <script src="<?php echo e(url('assets/js/lib/jquery-3.4.1.min.js')); ?>"></script>
    <!-- Bootstrap-->
    <script src="<?php echo e(url('assets/js/lib/popper.min.js')); ?>"></script>
    <script src="<?php echo e(url('assets/js/lib/bootstrap.min.js')); ?>"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.2.3/dist/ionicons/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="<?php echo e(url('assets/js/plugins/owl-carousel/owl.carousel.min.js')); ?>"></script>
    <!-- jQuery Circle Progress -->
    <script src="<?php echo e(url('assets/js/plugins/jquery-circle-progress/circle-progress.min.js')); ?>"></script>
    <!-- Base Js File -->
    <script src="<?php echo e(url('assets/js/base.js')); ?>"></script>


</body>

</html>
<?php /**PATH /www/wwwroot/appgmp.entorno-virtual.com/resources/views/frontend/login.blade.php ENDPATH**/ ?>