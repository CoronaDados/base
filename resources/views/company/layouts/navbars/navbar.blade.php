@auth()
    @include('company.layouts.navbars.navs.auth')
@endauth

@guest()
    @include('company.layouts.navbars.navs.guest')
@endguest
