<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main"
                aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('company.home') }}">
            <img src="{{ asset('argon') }}/img/brand/blue.png" class="navbar-brand-img" alt="...">
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        <i class="ni ni-single-02"></i>
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Olá!') }}</h6>
                    </div>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Configurações') }}</span>
                    </a>
                    <a href="{{ route('company.tips') }}" class="dropdown-item">
                        <i class="ni ni-support-16"></i>
                        <span>{{ __('Dicas') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('company.logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Sair') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('company.home') }}">
                            <img src="{{ asset('argon') }}/img/brand/blue.png">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse"
                                data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false"
                                aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item {{request()->is('/') ? 'active' : ''}}">
                    <a class="nav-link" href="{{ route('company.home') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
                    </a>
                </li>
                @can('Cadastrar Colaborador')
                    <li class="nav-item {{request()->is('person') ? 'active' : ''}}">
                        <a class="nav-link" href="#navbar-colaboradores" data-toggle="collapse" role="button"
                           aria-expanded="false"
                           aria-controls="navbar-colaboradores">
                            <i class="ni ni-single-02 text-blue"></i>
                            <span class="nav-link-text">{{ __('Colaboradores') }}</span>
                        </a>

                        <div class="collapse " id="navbar-colaboradores">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item {{request()->is('person') ? 'active' : ''}}">
                                    <a class="nav-link" href="{{ route('person.index') }}">
                                        {{ __('Listar') }}
                                    </a>
                                </li>

                                <li class="nav-item {{request()->is('person/create') ? 'active' : ''}}">
                                    <a class="nav-link" href="{{ route('person.create') }}">
                                        {{ __('Cadastrar') }}
                                    </a>
                                </li>

                                @can('Ver Funções')
                                    <li class="nav-item {{request()->segment(1) === 'roles' ? 'active' : ''}}">
                                        <a class="nav-link" href="{{ route('company.roles.index') }}">
                                            {{ __('Gerenciar Perfis') }}
                                        </a>
                                    </li>
                                @endcan

                                <li class="nav-item {{request()->segment(1) === 'roles' ? 'active' : ''}}">
                                    <a class="nav-link" href="{{ route('person.import') }}">
                                        {{ __('Importar') }}
                                    </a>
                                </li>

                            </ul>
                            <hr class="my-3">
                        </div>
                    </li>
                @endcan
                @can('Monitorar Colaborador')
                    <li class="nav-item {{request()->is('companies/monitoring') ? 'active' : ''}}">
                        <a class="nav-link" href="#navbar-monitoring" data-toggle="collapse" role="button"
                           aria-expanded="false"
                           aria-controls="navbar-monitoring">
                            <i class="ni ni-pin-3 text-orange"></i>
                            <span class="nav-link-text">{{ __('Monitoramento diário') }}</span>
                        </a>
                        <div class="collapse" id="navbar-monitoring">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item {{request()->is('companies/monitoring') ? 'active' : ''}}">
                                    <a class="nav-link" href="{{ route('company.monitoring') }}">
                                        {{ __('Monitorar') }}
                                    </a>
                                </li>

                                <li class="nav-item {{request()->is('companies/monitoring/history') ? 'active' : ''}}">
                                    <a class="nav-link" href="{{ route('company.monitoring.history') }}">
                                        {{ __('Histórico') }}
                                    </a>
                                </li>
                            </ul>
                            <hr class="my-3">
                        </div>
                    </li>
                @endcan
                <li class="nav-item">
                    <a href="{{ route('company.logout') }}" class="nav-link" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Sair') }}</span>
                    </a>
                </li>
            </ul>
            <hr class="my-3">
            <ul class="navbar-nav" style="position: absolute; bottom: 0;">
                <li class="nav-item  bg-info">
                    <a class="nav-link text-white" href="#" target="_blank">
                        <i class="ni ni-cloud-download-95"></i>Big Data
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
