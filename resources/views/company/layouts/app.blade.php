<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('company.layouts.google.head')

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Empresas contra o COVID-19</title>
    <!-- Favicon -->
    <link href="/favicon.png?v=2" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="/argon/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="/argon/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <!-- Argon CSS -->
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/css/dataTables.checkboxes.css"
        rel="stylesheet">
    <link type="text/css" href="/argon/css/argon.css?v=1.0.0" rel="stylesheet">
    <link type="text/css" href="/argon/css/custom.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body class="{{ $class ?? '' }}">
    @include('company.layouts.google.body')

    @auth()
    <form id="logout-form" action="{{ route('company.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @if(Route::current()->getName() != 'company.verification.notice')
    @include('company.layouts.navbars.sidebar')
    @endif
    @endauth

    <div class="main-content">
        @include('company.layouts.navbars.navbar')
        @include('flash::message')
        @yield('content')
    </div>

    @guest()
    @include('company.layouts.footers.guest')
    @endguest

    <script src="/argon/vendor/jquery/dist/jquery.min.js"></script>
    <script src="/argon/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/argon/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.11/js/dataTables.checkboxes.min.js">
    </script>
    <script src="/js/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    @stack('js')

    <!-- Argon JS -->
    <script src="/argon/js/argon.js?v=1.0.0"></script>
    <script>
        $('#flash-overlay-modal').modal();
            $('div.alert').not('.alert-important').delay(5000).fadeOut(350);
    </script>
    <script>
        $(document).ready(function(){
            $.fn.dataTable.ext.errMode = 'none';
            $.jMaskGlobals.watchDataMask = true;

            var SPMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };

            $('.phone').mask(SPMaskBehavior, spOptions);
            $('.date').mask('00/00/0000');
            $('.time').mask('00:00:00');
            $('.date_time').mask('00/00/0000 00:00:00');
            $('.cep').mask('00000-000');
            $('.mixed').mask('AAA 000-S0S');
            $('.cpf').mask('000.000.000-00', {reverse: true});
            $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
            $('.money').mask('000.000.000.000.000,00', {reverse: true});
            $('.money2').mask("#.##0,00", {reverse: true});
            $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
                translation: {
                    'Z': {
                        pattern: /[0-9]/, optional: true
                    }
                }
            });
            $('.ip_address').mask('099.099.099.099');
            $('.percent').mask('##0,00%', {reverse: true});
            $('.clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
            $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
            $('.fallback').mask("00r00r0000", {
                translation: {
                    'r': {
                        pattern: /[\/]/,
                        fallback: '/'
                    },
                    placeholder: "__/__/____"
                }
            });
            $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});
        });
    </script>
</body>

</html>
