@auth()
    @include('_layouts.navbars.navs.auth')
@endauth

@guest()
    @include('_layouts.navbars.navs.guest')
@endguest
