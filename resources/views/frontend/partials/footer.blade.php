<div class="appBottomMenu">
    <a href="{{ route('equipos.index') }}" class="item">
        <div class="col">
            <ion-icon name="train-outline" role="img" class="md hydrated" aria-label="chatbubble ellipses outline"></ion-icon>
            <span class="badge badge-danger">5</span>
        </div>
    </a>
    <a href="{{ route('baterias.index') }}" class="item">
        <div class="col">
            <ion-icon name="battery-charging-outline" role="img" class="md hydrated" aria-label="cube outline"></ion-icon>
        </div>
    </a>
    <a href="{{ route('dashboard') }}" class="item">
        <div class="col">
            <ion-icon name="home-outline" role="img" class="md hydrated" aria-label="home outline"></ion-icon>
        </div>
    </a>
    <a href="app-pages.html" class="item active">
        <div class="col">
            <ion-icon name="layers-outline" role="img" class="md hydrated" aria-label="layers outline"></ion-icon>
        </div>
    </a>
    <a href="javascript:;" class="item" data-toggle="modal" data-target="#sidebarPanel">
        <div class="col">
            <ion-icon name="menu-outline" role="img" class="md hydrated" aria-label="menu outline"></ion-icon>
        </div>
    </a>
</div>
<!-- app footer -->
<div class="appFooter">
    <img src="assets/img/logo.png" alt="icon" class="footer-logo mb-2">
    <div class="footer-title">
        Copyright © Mobilekit 2021. All Rights Reserved.
    </div>
    <div>Mobilekit is PWA ready Mobile UI Kit Template.</div>
    Great way to start your mobile websites and pwa projects.

    <div class="mt-2">
        <a href="javascript:;" class="btn btn-icon btn-sm btn-facebook">
            <ion-icon name="logo-facebook"></ion-icon>
        </a>
        <a href="javascript:;" class="btn btn-icon btn-sm btn-twitter">
            <ion-icon name="logo-twitter"></ion-icon>
        </a>
        <a href="javascript:;" class="btn btn-icon btn-sm btn-linkedin">
            <ion-icon name="logo-linkedin"></ion-icon>
        </a>
        <a href="javascript:;" class="btn btn-icon btn-sm btn-instagram">
            <ion-icon name="logo-instagram"></ion-icon>
        </a>
        <a href="javascript:;" class="btn btn-icon btn-sm btn-whatsapp">
            <ion-icon name="logo-whatsapp"></ion-icon>
        </a>
        <a href="#" class="btn btn-icon btn-sm btn-secondary goTop">
            <ion-icon name="arrow-up-outline"></ion-icon>
        </a>
    </div>

</div>
<!-- * app footer -->

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
<!-- Jquery -->
<script src="{{ url('assets/js/lib/jquery-3.4.1.min.js') }}"></script>
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


<script>
/*
    setTimeout(() => {
        notification('notification-welcome', 5000);
    }, 2000);
    */
</script>

</body>

</html>
