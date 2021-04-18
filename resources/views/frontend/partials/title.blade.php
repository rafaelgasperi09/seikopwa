<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="{{ URL::previous() }}" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">{{ $title }}</div>
    <div class="right">

    </div>
</div>
<div class="header-large-title">
@empty(!$subtitle)
<h3 class="title">{{ $subtitle }}</h3>
@endempty
</div>
