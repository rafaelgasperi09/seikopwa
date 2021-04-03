<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Mobilekit Mobile UI Kit</title>
    <meta name="description" content="Mobilekit HTML Mobile UI Kit">
    <meta name="keywords" content="bootstrap 4, mobile template, cordova, phonegap, mobile, html" />
    <link rel="icon" type="image/png" href="<?php echo e(url('front/assets/img/favicon.png')); ?>" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(url('front/assets/img/icon/192x192.png')); ?>">
    <link rel="stylesheet" href="<?php echo e(url('front/assets/css/style.css')); ?>">
    <link rel="manifest" href="<?php echo e(url('front/__manifest.json')); ?>">
</head>

<body>

<!-- loader -->
<div id="loader">
    <div class="spinner-border text-primary" role="status"></div>
</div>
<!-- * loader -->

<?php echo $__env->make('frontend.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



<!-- App Capsule -->
<div id="appCapsule">
    <?php echo $__env->yieldContent('content'); ?>


   <?php echo $__env->make('frontend.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</div>
<!-- * App Capsule -->

<?php echo $__env->make('frontend.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- welcome notification  -->
<div id="notification-welcome" class="notification-box">
    <div class="notification-dialog android-style">
        <div class="notification-header">
            <div class="in">
                <img src="assets/img/icon/72x72.png" alt="image" class="imaged w24">
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
<!-- * welcome notification -->

<!-- ///////////// Js Files ////////////////////  -->
<script src="<?php echo e(url('serviceworker.js')); ?>"></script>
<!-- ///////////// Js Files ////////////////////  -->
<!-- Jquery -->
<script src="<?php echo e(url('front/assets/js/lib/jquery-3.4.1.min.js')); ?>"></script>
<!-- Bootstrap-->
<script src="<?php echo e(url('front/assets/js/lib/popper.min.js')); ?>"></script>
<script src="<?php echo e(url('front/assets/js/lib/bootstrap.min.js')); ?>"></script>
<!-- Ionicons -->
<script type="module" src="https://unpkg.com/ionicons@5.2.3/dist/ionicons/ionicons.js"></script>
<!-- Owl Carousel -->
<script src="<?php echo e(url('front/assets/js/plugins/owl-carousel/owl.carousel.min.js')); ?>"></script>
<!-- jQuery Circle Progress -->
<script src="<?php echo e(url('front/assets/js/plugins/jquery-circle-progress/circle-progress.min.js')); ?>"></script>
<!-- Base Js File -->
<script src="<?php echo e(url('front/assets/js/base.js')); ?>"></script>


<script>
    setTimeout(() => {
        notification('notification-welcome', 5000);
    }, 2000);
</script>

</body>

</html>
<?php /**PATH C:\Server\www\seikopwa\resources\views/frontend/main-layout.blade.php ENDPATH**/ ?>