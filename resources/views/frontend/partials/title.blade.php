<div class="appHeader bg-primary text-light">
    <div class="left">
    @php
    $routeName = Route::currentRouteName();
    @endphp
        @if($routeName=='dashboard')
        <div class="left">
        <a href="#" class="headerButton" data-toggle="modal" data-target="#sidebarPanel">
            <ion-icon name="menu-outline"></ion-icon>
        </a>
        </div>
        @else
        <a href="{{ URL::previous()  }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
        </a>
        @endif
    </div>
    <div class="pageTitle">{{ $title }}</div>
    <div class="right">

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
