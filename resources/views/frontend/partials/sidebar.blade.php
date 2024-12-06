@if(\Sentinel::check())
<!-- App Sidebar -->
<div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <!-- profile box -->
                <div class="profileBox">
                    <div class="image-wrapper">
                        <a href="{{ route('usuarios.profile',current_user()->id) }}">
                            @empty($data->photo)
                                <img src="{{url('assets/img/user.png')}}" alt="image" class="imaged rounded">
                            @else
                                <img src="{{ \Storage::url($data->photo)}}" alt="image" class="imaged rounded">
                            @endif
                        </a>
                    </div>
                    <div class="in">
                        <strong>{{ current_user()->getFullName() }}</strong>
                        <div class="text-muted">
                            <ion-icon name="business"></ion-icon>
                            {{ current_user()->roles()->first()->long_name }}
                        </div>
                    </div>
                    <a href="javascript:;" class="close-sidebar-button" data-dismiss="modal">
                        <ion-icon name="close"></ion-icon>
                    </a>
                </div>
                <!-- * profile box -->

                <ul class="listview flush transparent no-line image-listview mt-2">
                    <li>
                        <a href="{{ route('inicio') }}" class="item" title="Inicio">
                            <div class="icon-box bg-primary">
                                <ion-icon name="home-outline"></ion-icon>
                            </div>
                            <div >
                                Inicio
                            </div>
                        </a>
                    </li>
                    @if( \Sentinel::hasAccess('dashboard.gmp'))
                        @if(!current_user()->isCliente())
                        <li>
                            <a href="{{ route('dashboard.gmp',['id'=>'gmp']) }}" class="item" title="Dashboard">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="analytics-outline"></ion-icon>
                                </div>
                                <div >
                                    Dashboard GMP
                                </div>
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ route('dashboard.gmp',['id'=>'cliente']) }}" class="item" title="Dashboard">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="analytics-outline"></ion-icon>
                                </div>
                                <div >
                                    Dashboard Cliente
                                </div>
                            </a>
                        </li>
                    @endif
                    @if(\Sentinel::hasAccess('equipos.index'))
                    <li>
                        <a href="{{ route('equipos.index') }}" class="item" title="Equipos">
                            <div class="icon-box bg-primary">
                                <img src="{{url('/images/icons/mcwhite.png')}}" width="22px">
                                {{--}}<ion-icon name="train-outline" title="equipos"></ion-icon>{{--}}
                            </div>
                            <div class="in">
                                Equipos
                            </div>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ route('equipos.lista') }}" class="item" title="Listado">
                                    <div class="icon-box bg-primary">
                                        <img src="{{url('/images/icons/mcwhite.png')}}" width="22px">
                                        {{--}}<ion-icon name="train-outline" title="equipos"></ion-icon>{{--}}
                                    </div>
                                    <div class="in">
                                        Listado
                                    </div>
                                </a>
                            </li>
                        </ul>
                        @if(\Sentinel::hasAccess('equipos.historial'))
                        <ul>
                            <li>
                                <a href="{{ route('equipos.reportes_list') }}" class="item" title="Historial de reportes">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="list-outline" title="equipos"></ion-icon>
                                    </div>
                                    <div class="in">
                                        Historial de reportes
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <ul>
                            <li>
                                <a href="{{ route('equipos.daily_check_list') }}" class="item" title="Historial de daily check">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="list-outline" title="daily check"></ion-icon>
                                    </div>
                                    <div class="in">
                                        Daily Check resumen
                                    </div>
                                </a>
                            </li>
                        </ul>
                        @endif
                    </li>
                    @endif
                    @if(\Sentinel::hasAccess('baterias.index'))
                    <li>
                        <a href="{{ route('baterias.index') }}" class="item"  title="Baterias">
                            <div class="icon-box bg-primary">
                                <ion-icon name="battery-charging-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <div>Baterias</div>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if(\Sentinel::hasAccess('usuarios.index'))
                    <li>
                        <a href="{{ route('usuarios.index') }}" class="item"  title="Usuarios">
                            <div class="icon-box bg-primary">
                                <ion-icon name="people-circle-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <div>Usuarios</div>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if(\Sentinel::hasAccess('role.index'))
                    <li>
                        <a href="{{ route('role.index') }}" class="item"  title="Roles">
                            <div class="icon-box bg-primary">
                                <ion-icon name="settings-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <div>Roles</div>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if(\Sentinel::hasAccess('equipos.calendar'))
                        <li>
                            <a href="{{ route('equipos.calendar') }}" class="item"  title="Calendario">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="calendar-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    <div>Calendario</div>
                                </div>
                            </a>
                        </li>
                    @endif
                    <li>
                        <div class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="moon-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <div>Dark Mode</div>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input dark-mode-switch"
                                           id="darkmodesidebar">
                                    <label class="custom-control-label" for="darkmodesidebar"></label>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- sidebar buttons -->
            <div class="sidebar-buttons">
                @if(\Sentinel::hasAccess('usuarios.profile'))
                <a href="{{ route('usuarios.profile',Sentinel::getUser()->id) }}" class="button">
                    <ion-icon name="person-outline"></ion-icon>
                </a>
                @endif
                @if(\Sentinel::hasAccess('role.index'))
                <a href="{{ route('role.index') }}" class="button">
                    <ion-icon name="settings-outline"></ion-icon>
                </a>
                @endif
                <a href="{{ route('logout') }}" class="button">
                    <ion-icon name="log-out-outline"></ion-icon>
                </a>
            </div>
            <!-- * sidebar buttons -->
        </div>
    </div>
</div>
<!-- * App Sidebar -->
<script>
 function actualizaIcono(dmactivo){
    if(dmactivo){
        $('#equipos_icon').attr("src","{{url('/images/icons/mcwhite.png')}}");
    }else{
        $('#equipos_icon').attr("src","{{url('/images/icons/mc.png')}}");
    }
 }
    
 $( document ).ready(function() {
    var dmactivo=$('body').hasClass("dark-mode-active");

    actualizaIcono(dmactivo);

    $('#darkmodesidebar').on('change',function(){
    dmactivo=$('#darkmodesidebar').is(":checked");
    actualizaIcono(dmactivo);

});
});




    
</script>
@endif
