@extends('_layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    {{--    @include('layouts.headers.cards')--}}
    <div class="container-fluid pt-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Cadastro de colaboradores</h5>
                <form>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" id="cpf" placeholder="CPF">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="custom-select">
                                    <option disabled selected>Setor</option>
                                    <option value="1">Operacional</option>
                                    <option value="2">Escritório</option>
                                    <option value="3">Almoxerifado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" id="cep" placeholder="CEP">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    <input class="form-control datepicker" placeholder="Data de Nascimento" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="number" class="form-control" id="people_in_residence"
                                       onchange="ShowForm1()" placeholder="Mora com quantas pessoas">
                            </div>
                        </div>
                    </div>
                    <div id="show1">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Dados da pessoas na mesma residencia</h5>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="custom-select" onselect="ShowForm2()">
                                    <option disabled selected>Como vai ao trabalho</option>
                                    <option value="1">Onibus</option>
                                    <option value="2">Carro</option>
                                    <option value="3">A pé</option>
                                    <option value="3">bicicleta</option>
                                </select>
                            </div>
                        </div>
                        <div id="show2">
                            <div class="card">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div>

        </div>
        <div class="row mt-5">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Lista de Colaboradores</h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Idade</th>
                                <th scope="col">Setor</th>
                                <th scope="col">CEP</th>
                                <th scope="col">Deslocamento</th>
                                <th scope="col">Pessoas na mesma residencia</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">Fulano de Tal</th>
                                <td>40</td>
                                <td>Administrativo</td>
                                <td>88370-555</td>
                                <td>Carro</td>
                                <td>2</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{--        @include('layouts.footers.auth')--}}
    </div>
@endsection

@push('js')
    <script>
        var ShowForm1 = (function () {
            let numpeoples = $('#people_in_residence').val()
            if (numpeoples > 0) {
                let template =
                    '<div class="row m-2">\n' +
                    '                            <div class="col-md-6">\n' +
                    '                                <div class="form-group">\n' +
                    '                                    <input type="text" placeholder="Nome" class="form-control"/>\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                            <div class="col-md-6">\n' +
                    '                                <div class="form-group">\n' +
                    '                                    <input type="tel" placeholder="Telefone" class="form-control"/>\n' +
                    '                                </div>\n' +
                    '                            </div>\n' +
                    '                        </div>'
                for (var i = 0; i < numpeoples; i++) {
                    $('#show1').append(template)
                }

                $('#show1').show()
            } else {
                $('#show1').hide()
            }
        })
        $(document).ready(function () {
            ShowForm1();

        })
    </script>
@endpush
