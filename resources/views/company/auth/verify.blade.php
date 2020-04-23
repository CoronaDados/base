@extends('company.layouts.app', ['class' => 'bg-default'])

@section('content')
    @include('company.layouts.headers.guest')

    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small>{{ __('Confirme seu e-mail') }}</small>
                        </div>
                        <div>
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('Um novo link de verificação foi enviado para o e-mail <strong>' . Auth::user()->email . '</strong>.') }}
                                </div>
                            @endif

                            {!! __('Para usar sua conta no ' . config('app.name') . ', confirme o endereço de e-mail no link que foi enviado para <strong>' . Auth::user()->email . '</strong>.') !!}
                            @if (Route::has('company.verification.resend'))
                                {{ __('Se você não recebeu o e-mail') }},
                                <form class="d-inline" method="POST" action="{{ route('company.verification.resend') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Clique aqui para enviar novamente') }}</button>.
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
