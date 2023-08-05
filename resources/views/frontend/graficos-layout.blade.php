@include('frontend.partials.header2')
<!-- App Capsule -->
<div id="appCapsule">
    <style>
        .grafictitle{
        background: rgb(30 116 253) !important;
        /*background: linear-gradient(87deg, rgba(227,172,19,1) 35%, rgba(247,232,0,1) 100%) !important;*/
        color:#FFF !important;
        padding:5px !important;
        }
    </style>
    @php
    $col=4;
    if($data['max']>3)
        $col=4;
    if($data['max']>10)
        $col=6;

    @endphp
    <script>
    var colors = [
        '#66A2DB',
        '#F57F32',
        '#A8A8A8',
        '#FFC736',
        '#FF4560',
        ];
    var colors = [
        '#FE9500',
        '#1122A4',
        '#0D8EFF',
        '#BBB22F',
        '#764FA0',
        '#EC3EAD',
        '#76007C',
        '#E96F3C',
        '#7209b7',
        '#e09f3e',
        '#f7a9a8',
                ];

    </script>
    @include('frontend.partials.message')
    @yield('content')
</div>
<!-- * App Capsule -->
@include('frontend.partials.sidebar')
@include('frontend.partials.footer')
