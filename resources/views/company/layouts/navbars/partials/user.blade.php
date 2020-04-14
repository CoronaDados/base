<div class=" dropdown-header noti-title">
    <h6 class="text-overflow m-0">{{ __('Olá!') }}</h6>
</div>
@if(auth('company')->user()->hasVerifiedEmail())
    <a href="{{ route('person.profile') }}" class="dropdown-item">
        <i class="ni ni-single-02"></i>
        <span>{{ __('Meus Dados') }}</span>
    </a>
    <a href="{{ route('company.tips') }}" class="dropdown-item">
        <i class="ni ni-bulb-61"></i>
        <span>{{ __('Dicas') }}</span>
    </a>
    <a href="{{ route('company.help') }}" class="dropdown-item">
        <i class="ni ni-support-16"></i>
        <span>{{ __('Ajuda') }}</span>
    </a>
@endif
<div class="dropdown-divider"></div>
<a href="{{ route('company.logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="ni ni-user-run"></i>
    <span>{{ __('Sair') }}</span>
</a>