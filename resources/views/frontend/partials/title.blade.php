<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline" role="img" class="md hydrated" aria-label="chevron back outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">{{ $title }}</div>
    <div class="right">

    </div>
</div>
@empty(!$subtitle)
<div class="header-large-title">
    <h5 class="title">{{ $subtitle }}</h5>
</div>
@endempty
