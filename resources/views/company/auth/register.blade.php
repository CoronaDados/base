@extends('company.layouts.app', ['class' => 'bg-info'])

@section('content')
    @include('company.layouts.headers.guest')

    <div class="container mt--8 pb-5">
        <!-- Table -->
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small>{{ __('Cadastre sua empresa') }}</small>
                        </div>
                        <form role="form" method="POST" action="{{ route('company.register') }}">
                            @csrf

                            <div class="form-group{{ $errors->has('razao') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-industry" aria-hidden="true"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Razão social') }}" type="text" name="razao" value="{{ old('razao') }}" required autofocus>
                                </div>
                                @if ($errors->has('razao'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('razao') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('cnpj') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-building" aria-hidden="true"></i></span>
                                    </div>
                                    <input class="form-control cnpj{{ $errors->has('cnpj') ? ' is-invalid' : '' }}" placeholder="{{ __('CNPJ') }}" type="text" name="cnpj" value="{{ old('cnpj') }}" required>
                                </div>
                                @if ($errors->has('cnpj'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('cnpj') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-user-circle" aria-hidden="true"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Nome do responsável') }}" type="text" name="name" value="{{ old('name') }}" required autofocus>
                                </div>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('cpf') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-address-card" aria-hidden="true"></i></span>
                                    </div>
                                    <input class="form-control cpf{{ $errors->has('cpf') ? ' is-invalid' : '' }}" placeholder="{{ __('CPF do responsável') }}" type="text" name="cpf" value="{{ old('cpf') }}" required>
                                </div>
                                @if ($errors->has('cpf'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('cpf') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email do responsável') }}" type="email" name="email" value="{{ old('email') }}" required>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Senha') }}" type="password" name="password" required>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="{{ __('Repita a senha') }}" type="password" name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="row my-4">
                                <div class="col-12">
                                    <div class="custom-control custom-control-alternative custom-checkbox">
                                        <input class="custom-control-input" required id="customCheckRegister" type="checkbox">
                                        <label class="custom-control-label" for="customCheckRegister">
                                        <span class="text-muted">{{ __('Eu concordo com os') }} <a href="#!" data-toggle="modal" data-target="#termoPrivacidade">{{ __('Termos de Privacidade') }}</a></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mt-4">{{ __('Cadastrar empresa') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <a href="{{ route('company.login') }}" class="text-secondary">
                            <small>Voltar</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="termoPrivacidade" tabindex="-1" role="dialog" aria-labelledby="termoPrivacidade" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="termoPrivacidade">Termo de Privacidade e de uso dos dados</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-md-7">
                            <div class="card bg-secondary shadow border-0">
                                <div class="card-body p-0">
                                    <p>O Sistema FIESC, composto pelas entidades FIESC, SESI, SENAI, IEL e CIESC, através desta Política de
                                        Privacidade (“Política”), reconhece a importância de garantir a segurança dos dados pessoais que são
                                        compartilhados pelos usuários ao acessarem a aplicação Corona Dados. Além disso, a "Política"
                                        esclarece quais as informações e os dados pessoais que serão coletados, além da forma que serão
                                        tratados durante o uso da aplicação.</p>
                                    <p>Ao fornecer seus dados pessoais, o usuário automaticamente declara conhecer e aceitar os termos desta
                                        Política. Serão assim considerados dados como: nome, telefone, e-mail, data de nascimento, sexo,
                                        números de CPF, CEP e se você faz parte ou não do grupo de riscos.</p>
                                    <p>Os dados pessoais poderão ser utilizados para as seguintes finalidades:</p>
                                    <ul>
                                        <li class="font-weight-light">Manter atualizados os cadastros dos usuários para fins de contato pelos meios de comunicação
                                            disponíveis, sendo correio eletrônico, sms, whatsapp e/ou telefone;</li>
                                        <li class="font-weight-light">Elaborar estatísticas gerais, sem que haja identificação dos usuários com o objetivo de garantir
                                            a proteção a vida;</li>
                                        <li class="font-weight-light">Responder as dúvidas e solicitações dos usuários.</li>
                                    </ul>
                                    <p>Todos os dados pessoais coletados, são armazenados em base de dados por meio de mecanismos de
                                        criptografia e segurança da informação nos padrões de mercado, sendo tratados e compartilhados
                                        entre as entidades do Sistema FIESC e terceiros contratados para prestação de serviços. Estes
                                        terceiros são requeridos a usarem a informação pessoal que compartilharmos com eles apenas para
                                        prestarem os serviços que contratamos, em nosso nome e tratarem sua informação pessoal como
                                        estritamente confidencial.
                                    </p>
                                    <p>Este termo tem como objetivo expressar o consentimento de uso de seus dados pessoais, por parte da
                                        FIESC, que atuará como controlador de dados perante a Lei Geral de Proteção de dados (Lei 13.709).</p>
                                    <p>As informações compartilhadas com a FIESC serão tratadas durante o período indeterminado, até que o
                                        consentimento seja revogado.</p>
                                    <p>O Sistema FIESC assegura que a política de privacidade possui regras claras, precisas e em concordância
                                        com as leis vigentes.</p>
                                    <p>O Sistema FIESC poderá alterar os termos da política de privacidade sem qualquer aviso prévio, de forma
                                        que deverá ser verificada periodicamente.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
