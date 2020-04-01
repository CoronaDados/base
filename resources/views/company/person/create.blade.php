@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    {{--    @include('layouts.headers.cards')--}}
    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card">
            <div class="card-header text-center">
                <h3>Cadastrar Colaborador</h3>
                <h5></h5>
            </div>
            <div class="card-body">
                <form role="form" method="POST" action="{{ route('company.person.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nome Completo</label>
                                <input type="text" class="form-control" required id="name" name="name"
                                       placeholder="Nome Completo">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" required id="email" name="email"
                                       placeholder="Email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="phone">Telefone (WhatsApp)</label>
                                <input type="tel" class="form-control phone" required name="phone" id="phone"
                                       placeholder="Telefone (WhatsApp)">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cpf">CPF</label>
                                <input type="text" class="form-control cpf" required id="cpf" name="cpf"
                                       placeholder="CPF">
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
                                <input type="text" class="form-control cep" required name="cep" id="cep"
                                       placeholder="CEP">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="birthday">Data de Nascimento</label>
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                    </div>
                                    <input class="form-control" required placeholder="Data de Nascimento" id="birthday"
                                           name="birthday" type="date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type_transport">Como vai ao trabalho</label>
                                <select name="type_transport" id="type_transport" required class="custom-select"
                                        onchange="TypeTransport()">
                                    <option disabled selected>Como vai ao trabalho</option>
                                    <option value="1">Onibus</option>
                                    <option value="2">Carro</option>
                                    <option value="3">A pé</option>
                                    <option value="3">Bicicleta</option>
                                    <option value="4">Outros</option>
                                </select>
                            </div>
                        </div>
                        <div id="show_type_transport" class="col-md-4">
                            <div class="form-group">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 p-1">
                            <div class="card p-1">
                                <div class="card-header text-center">
                                    <h3>Relacionamento com pessoas</h3>
                                    <h5>Liste todas as pessoas em sua residencia, meio de transporte, meio de convivio
                                        etc</h5>
                                </div>
                                <div class="card-body" id="related_persons">
                                    <div class="row">
                                        <div class="col-6 p-1">
                                            <div class="form-group">
                                                <label>Nome</label>
                                                <input type="text" name="related_persons[1][name]" placeholder="Nome"
                                                       onchange="addP(1)" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="col-3 p-1">
                                            <div class="form-group">
                                                <label>Telefone</label>
                                                <input type="tel" name="related_persons[1][phone]"
                                                       placeholder="Telefone"
                                                       class="form-control phone"/>
                                            </div>
                                        </div>
                                        <div class="col-3 p-1">
                                            <div class="form-group">
                                                <label>CPF</label>
                                                <input type="text" name="related_persons[1][cpf]" placeholder="CPF"
                                                       class="form-control cpf"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary my-4">{{ __('Cadastrar') }}</button>
                    </div>
                </form>
            </div>
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
        var handleMasks = function (){
            var SPMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };

            $('.phone').mask(SPMaskBehavior, spOptions);
            $('.cpf').mask('000.000.000-00', {reverse: true});
        };
        var TypeTransport = (function () {
            let type = $('#type_transport').val();
            let template = null;
            switch (type) {
                case '1': {
                    template =
                        '<label for="bus_line">Número da linha de onibus</label>\n' +
                        '<input type="text" class="form-control" name="bus_line"  id="bus_line"\n' +
                        'placeholder="Número da linha de onibus">'
                }
            }
            console.log(template)
            if (template) {
                $('#show_type_transport > div').html(template)
                $('#show_type_transport').show()
            } else {
                $('#show_type_transport').hide()
            }
        })
        let c = 1;
        var addP = (count) =(function (count) {

            console.log('count' + count)
            console.log('c' + c)
            if(count != c)
                return true;
            count++;
            c =count;
            var newInput = $(document.createElement('div'))
                .attr("class", 'row');

            newInput.after().html(
                '<div class="col-6 p-sm-1">\n' +
                '    <div class="form-group">\n' +
                '        <label>Nome</label>\n' +
                '        <input type="text" name="related_persons['+count+'][name]" placeholder="Nome"\n' +
                '            onchange="addP('+count+')" class="form-control"/>\n' +
                '    </div>\n' +
                '</div>\n' +
                '<div class="col-3 p-sm-1">\n' +
                '    <div class="form-group">\n' +
                '        <label>Telefone</label>\n' +
                '        <input type="tel" name="related_persons['+count+'][phone]" placeholder="Telefone"\n' +
                '               class="form-control phone"/>\n' +
                '    </div>\n' +
                '</div>\n' +
                '<div class="col-3 p-sm-1">\n' +
                '    <div class="form-group">\n' +
                '        <label>CPF</label>\n' +
                '        <input type="text" name="related_persons['+count+'][cpf]" placeholder="CPF"\n' +
                '               class="form-control cpf"/>\n' +
                '    </div>\n' +
                '</div>');

            newInput.appendTo("#related_persons");
            handleMasks();
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
                ajax: "{{ route('company.person.create') }}",
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
