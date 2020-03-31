@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    {{--    @include('layouts.headers.cards')--}}
    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Adicionar usuário</h5>
                <form role="form" method="POST" action="{{ route('company.users.create') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nome Completo</label>
                                <input type="text" class="form-control" required id="name" name="name" placeholder="Nome Completo">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" required id="email" name="email" placeholder="Email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="phone">Telefone (WhatsApp)</label>
                                <input type="tel" class="form-control phone" required name="phone" id="phone" placeholder="Telefone (WhatsApp)">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cpf">CPF</label>
                                <input type="text" class="form-control cpf" required id="cpf" name="cpf" placeholder="CPF">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="department">Departamento</label>
                                <select name="department" id="department" required class="custom-select">
                                    <option disabled selected>Setor</option>
                                    <option value="Operacional">Operacional</option>
                                    <option value="Escritório">Escritório</option>
                                    <option value="Almoxerifado">Almoxerifado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cep">CEP</label>
                                <input type="text" class="form-control cep" required name="cep" id="cep" placeholder="CEP">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="birthday">Data de Nascimento</label>
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    <input class="form-control" required placeholder="Data de Nascimento" id="birthday" name="birthday" type="date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type_transport">Como vai ao trabalho</label>
                                <select name="type_transport" id="type_transport" required class="custom-select" onselect="TypeTransport()">
                                    <option disabled selected>Como vai ao trabalho</option>
                                    <option value="1">Onibus</option>
                                    <option value="2">Carro</option>
                                    <option value="3">A pé</option>
                                    <option value="3">Bicicleta</option>
                                    <option value="4">Outros</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="show_related_persons">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Dados das pessoas na mesma residencia</h5>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="persons_in_residence">Mora com quantas pessoas</label>
                                <input type="number" class="form-control"  id="persons_in_residence"
                                       oninput="ShowRelatedPersons()" placeholder="Mora com quantas pessoas">
                            </div>
                        </div>
                        <div id="show2">
                            <div class="card">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary my-4">{{ __('Cadastrar') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <div>

        </div>


    </div>
@endsection

@push('js')
    <script>
        var ShowRelatedPersons = (function () {
            let numpeoples = $('#persons_in_residence').val()
           // $('#show_related_persons').html('')
            if (numpeoples > 0) {
                let template =
                    '<div class="row m-2">\n' +
                    '                            <div class="col-md-6">\n' +
                    '                                <div class="form-group">\n' +
                    '                                    <input type="text" name="related_persons[name][]" placeholder="Nome" class="form-control"/>\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                            <div class="col-md-5">\n' +
                    '                                <div class="form-group">\n' +
                    '                                    <input type="tel" name="related_persons[phone][]" placeholder="Telefone" class="form-control"/>\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                           <div class="col-md-1">' +
                    '                           <div class="form-group">' +
                    '<button class="form-control btn">Teste</button>' +
                    '                           </div></div>' +
                    '                        </div>'
                for (var i = 0; i < numpeoples; i++) {
                    $('#show_related_persons').append(template)
                }

                $('#show_related_persons').show()
            } else {
                $('#show_related_persons').hide()
            }
        })
    </script>
@endpush
