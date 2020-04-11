<form role="form" {{ $route ?? 'method="POST" action="' . $route . '"' }}>
    <input type="hidden" name="user_id" id="user_id">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Nome Completo {{ $isRequired ? '*' : '' }}</label>
                <input type="text" class="form-control" required id="name" name="name"
                       placeholder="Nome Completo {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}/>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="email">Email {{ $isRequired ? '*' : '' }}</label>
                <input type="email" class="form-control" required id="email" name="email"
                       placeholder="Email {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="phone">Telefone (WhatsApp) {{ $isRequired ? '*' : '' }}</label>
                <input type="tel" class="form-control phone" required name="phone" id="phone"
                       placeholder="Telefone (WhatsApp) {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}/>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="cpf">CPF {{ $isRequired ? '*' : '' }}</label>
                <input type="text" class="form-control cpf" required id="cpf" name="cpf"
                       placeholder="CPF {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}/>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="department">Departamento</label>
                <select name="department" id="department" {{ $isRequired ? 'required' : '' }} class="custom-select">
                    <option disabled selected>Setor</option>
                    <option value="Administrativo">Administrativo</option>
                    <option value="Financeiro">Financeiro</option>
                    <option value="Operacional">Operacional</option>
                    <option value="Comercial">Comercial</option>
                    <option value="Médico">Médico</option>
                    <option value="Diretoria">Diretoria</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="cep">CEP {{ $isRequired ? '*' : '' }}</label>
                <input type="text" class="form-control cep" {{ $isRequired ? 'required' : '' }} name="cep" id="cep"
                       placeholder="CEP {{ $isRequired ? '(obrigatório)' : '' }}" {{ $isRequired ? 'required' : '' }}/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="birthday">Data de Nascimento {{ $isRequired ? '*' : '' }}</label>
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                    </div>
                    <input class="form-control" {{ $isRequired ? 'required' : '' }} placeholder="Data de Nascimento" id="birthday"
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
                    <option value="1">Ônibus</option>
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
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="password">Senha (deixe em branco para não alterar)</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="confirm_password">Confirme a senha (se for alterar)</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control">
            </div>
        </div>
    </div>

    @if($isRequired)
    <div class="row">
        <div class="col-12 p-1">
            <div class="card p-1">
                <div class="card-header text-center">
                    <h3>Relacionamento com pessoas</h3>
                    <h5>Liste todas as pessoas em sua residência, meio de transporte, meio de convívio etc</h5>
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
    @endif

    <div class="col-12 text-center">
        <button type="submit" class="btn btn-primary my-4">{{ $isRequired ? __('Cadastrar') : __('Salvar') }}</button>
    </div>
</form>

@push('js')
    <script>
        var handleMasks = function () {
            var SPMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                spOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };

            $('#phone').mask(SPMaskBehavior, spOptions);
            $('#cpf').mask('000.000.000-00');
        };

        var TypeTransport = (function () {
            let type = $('#type_transport').val();
            let template = null;
            switch (type) {
                case '1': {
                    template =
                        '<label for="bus_line">Número da linha de ônibus</label>\n' +
                        '<input type="text" class="form-control" name="bus_line"  id="bus_line"\n' +
                        'placeholder="Número da linha de ônibus">'
                }
            }
            if (template) {
                $('#show_type_transport > div').html(template)
                $('#show_type_transport').show()
            } else {
                $('#show_type_transport').hide()
            }
        });

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
                serverSide: false,
                ajax: "{{ $ajaxRoute }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'lider', name: 'lider'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endpush
