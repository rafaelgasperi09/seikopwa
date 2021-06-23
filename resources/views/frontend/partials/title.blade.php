<div class="appHeader bg-primary text-light">
    <div class="left">
    @php
    $routeName = Route::currentRouteName();
    @endphp
        @if($routeName=='dashboard')
        <div class="left">
        <a href="#" class="headerButton" data-toggle="modal" data-target="#sidebarPanel" title="Menú">
            <ion-icon name="menu-outline"></ion-icon>
        </a>
        </div>
        @else
            <a href="@isset($route_back) {{ $route_back }} @else {{ URL::previous() }}@endisset" class="headerButton goBack" title="Regresar">
                <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
            </a>
        @endif
    </div>
    <div class="pageTitle">{{ $title }}</div>
    <div class="right">
        <a href="{{ route('logout') }}" class="headerButton" title="Cerrar sesión">
            <ion-icon name="log-out-outline"></ion-icon>
        </a>
    </div>
</div>
<div class="header-large-title">
@empty(!$subtitle)
<h3 class="title">
    @isset($image)
        <img src="{{ $image }}" alt="image" >
    @endisset
    {{ $subtitle }}
</h3>
@endempty

</div>
