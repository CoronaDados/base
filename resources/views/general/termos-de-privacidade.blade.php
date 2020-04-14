@extends('company.layouts.app', ['class' => 'bg-default'])

@section('content')
    @include('company.layouts.headers.guest')

    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <h1>Termo de Privacidade e de uso dos dados</h1>
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
@endsection
