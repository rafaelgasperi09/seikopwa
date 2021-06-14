@if(\Sentinel::check())
<!-- App Sidebar -->
<div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <!-- profile box -->
                <div class="profileBox">
                    <div class="image-wrapper">
                        @empty($data->photo)
                            <img src="{{url('assets/img/user.png')}}" alt="image" class="imaged rounded">
                        @else
                            <img src="{{ \Storage::url($data->photo)}}" alt="image" class="imaged rounded">
                        @endif
                    </div>
                    <div class="in">
                        <strong>{{ current_user()->getFullName() }}</strong>
                        <div class="text-muted">
                            <ion-icon name="business"></ion-icon>
                            {{ current_user()->roles()->first()->name }}
                        </div>
                    </div>
                    <a href="javascript:;" class="close-sidebar-button" data-dismiss="modal">
                        <ion-icon name="close"></ion-icon>
                    </a>
                </div>
                <!-- * profile box -->

                <ul class="listview flush transparent no-line image-listview mt-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="item" title="Dashboard">
                            <div class="icon-box bg-primary">
                                <ion-icon name="home-outline"></ion-icon>
                            </div>
                            <div >
                                Dashboard
                            </div>
                        </a>
                    </li>
                    @if(\Sentinel::hasAccess('equipos.index'))
                    <li>
                        <a href="{{ route('equipos.index') }}" class="item" title="Equipos">
                            <div class="icon-box bg-primary">
                                <ion-icon name="train-outline" title="equipos"></ion-icon>
                            </div>
                            <div class="in">
                                Equipo
                            </div>
                        </a>
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
@endif
