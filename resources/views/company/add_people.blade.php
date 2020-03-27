@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    {{--    @include('layouts.headers.cards')--}}
    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Cadastro de colaboradores</h5>
                <form role="form" method="POST" action="{{ route('company.add_people') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" required name="name" placeholder="Nome Completo">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" class="form-control" required name="email" placeholder="Email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="tel" class="form-control phone" required name="phone" placeholder="Telefone">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control cpf" required name="cpf" placeholder="CPF">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="sector" required class="custom-select">
                                    <option disabled selected>Setor</option>
                                    <option value="Operacional">Operacional</option>
                                    <option value="Escritório">Escritório</option>
                                    <option value="Almoxerifado">Almoxerifado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control cep" required name="cep" placeholder="CEP">
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
                                    <input class="form-control" required placeholder="Data de Nascimento" name="birthday" type="date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="number" class="form-control"  id="people_in_residence"
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
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary my-4">{{ __('Cadastrar') }}</button>
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
                                <h3 class="mb-0">Colaboradores cadastrados</h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive p-2">
                        <!-- Projects table -->
                        <table class="table table-bordered data-table">
                            <thead>
                            <tr>
                                <th width="20px">No</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Setor</th>
{{--                                <th width="100px"><Ações></Ações></th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
{{--        <div class="row mt-5">--}}
{{--            <div class="col-xl-12 mb-5 mb-xl-0">--}}
{{--                <div class="card shadow">--}}
{{--                    <div class="card-header border-0">--}}
{{--                        <div class="row align-items-center">--}}
{{--                            <div class="col">--}}
{{--                                <h3 class="mb-0">Lista de Colaboradores</h3>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="table-responsive">--}}
{{--                        <!-- Projects table -->--}}
{{--                        @if(isset($peoples))--}}
{{--                        <table class="table align-items-center table-flush">--}}
{{--                            <thead class="thead-light">--}}
{{--                            <tr>--}}
{{--                                <th scope="col">Nome</th>--}}
{{--                                <th scope="col">Idade</th>--}}
{{--                                <th scope="col">Setor</th>--}}
{{--                                <th scope="col">CEP</th>--}}
{{--                                <th scope="col">Deslocamento</th>--}}
{{--                                <th scope="col">Pessoas na mesma residencia</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            @foreach($peoples as $people)--}}
{{--                            <tr>--}}
{{--                                <th scope="row">{{$people->name}}</th>--}}
{{--                                <td>{{$people->birthday}}</td>--}}
{{--                                <td></td>--}}
{{--                                <td>{{$people->cep}}</td>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                            </tr>--}}
{{--                                @endforeach--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                            @else--}}
{{--                            <div class="card-header border-0">--}}
{{--                                <div class="row align-items-center">--}}
{{--                                    <div class="col">--}}
{{--                                        <h3 class="mb-0">Nenhum colaborador cadastrado</h3>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

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

        $(function () {

            var table = $('.data-table').DataTable({
                language: {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_ resultados por página",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    },
                    "select": {
                        "rows": {
                            "_": "Selecionado %d linhas",
                            "0": "Nenhuma linha selecionada",
                            "1": "Selecionado 1 linha"
                        }
                    }
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('company.add_people') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'sector', name: 'sector'},
                    // {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

        });

    </script>
@endpush
