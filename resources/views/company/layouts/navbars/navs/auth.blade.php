<!-- Top navbar -->
<nav class="navbar navbar-top fixed-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="{{ route('company.home') }}">{{ auth()->user()->company->razao }}</a>
        <!-- Form -->
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <i class="ni ni-single-02"></i>
                        </span>
                        <div class="media-body ml-2 d-none d-lg-block">
                            <span class="mb-0 text-sm  font-weight-bold">{{ auth()->user()->person->name }}</span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Olá!') }}</h6>
                    </div>
                    @can('Ver Usuários')
                    <a href="{{route('company.users.index')}}" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Usuários') }}</span>
                    </a>
                    @endcan
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
    </div>
</nav>
