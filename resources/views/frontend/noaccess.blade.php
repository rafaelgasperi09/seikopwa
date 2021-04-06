@extends('frontend.main-layout')
@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ URL::to('dashboard') }}">Dashboard</a></li>
        <li class="active">404</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <div class="error-container">
                    <div class="error-code">403</div>
                    <div class="error-text">Acceso denegado</div>
                    <div class="error-subtext">Disculpe su usuario no tiene permisos para acceder a esta página.</div>
                    <div class="error-subtext">Si piensa que debería tener accesso pongase en contacto con el administrador.</div>
                    <div class="error-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <a class="btn btn-danger btn-block btn-lg" href="{{ URL::previous() }}">Volver atras</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop
