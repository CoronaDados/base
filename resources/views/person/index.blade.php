@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')

    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card shadow">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">Lista de Colaboradores</h3>
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
                        <th>E-mail</th>
                        <th>Líder</th>
                        <th width="100px">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body pt-0">
                    <div class="col-lg-12 pl-0 pt-0 pr-0">
                        <div class="nav-wrapper">
                            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-text" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-text-1-tab" data-toggle="tab" href="#tabs-text-1" role="tab" aria-controls="tabs-text-1" aria-selected="true">Visualização / Edição</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-text-2-tab" data-toggle="tab" href="#tabs-text-2" role="tab" aria-controls="tabs-text-2" aria-selected="false">Histórico de Sintomas</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="tabs-text-1" role="tabpanel" aria-labelledby="tabs-text-1-tab">
                                @include('person.partials.form', [compact('riskGroups', 'sectors', 'roles'), 'isRequired' => false, 'route' => ''])
                            </div>

                            <div class="tab-pane fade" id="tabs-text-2" role="tabpanel" aria-labelledby="tabs-text-2-tab">
                                <div class="table-responsive">
                                    <!-- Projects table -->
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sintoma(s)</th>
                                                <th>Data e Hora do Monitoramento</th>
                                                <th>Monitorado por</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Tosse Seca</td>
                                                <td>12/04/2020 13:28</td>
                                                <td>Douglas</td>
                                            </tr>
                                        </tbody>
                                        <tfooter>
                                            <tr>
                                                <td colspan="3">Observações: Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, officiis.</td>
                                            </tr>
                                        </tfooter>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let table = $('.data-table').DataTable({
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
                            "sNext": "<i class=\"fas fa-angle-right\"class=\"fas fa-angle-right\">",
                            "sPrevious": "<i class=\"fas fa-angle-left\"class=\"fas fa-angle-left\">",
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
                    ajax: "{{ route('person.index') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'lider', name: 'lider'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });

            $('body').on('click', '.editPerson', function (e) {
                e.preventDefault();

                let person_id = $(this).data('id');

                $.ajax({
                    url: 'person/' + person_id,
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {
                        $('#modelHeading').html("Colaborador " + data.person.name);
                        $('#saveBtn').val("edit-user");
                        $('#ajaxModel').modal('show');

                        $('#person_id').val(person_id);
                        $('#name').val(data.person.name);
                        $('#email').val(data.person.email);
                        $('#phone').val(data.person.phone);
                        $('#cpf').val(data.person.cpf);
                        $('#sector').val(data.person.sector);
                        $('#risk_group').val(data.person.risk_group);

                        if(data.person.bithday) {
                            $('#birthday').val(formattedDateFromDB(data.person.bithday))
                        }

                        const $radios = $('input:radio[name=gender]');
                        if($radios.is(':checked') === false) {
                            $radios.filter('[value=' + data.person.gender + ']').prop('checked', true);
                        }

                        $('.cep-person').val(data.person.cep).mask('000000.000');

                        handleMasks();
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Erro!',
                            text: 'Erro ao carregar os dados, atualize a página.',
                            icon: 'error',
                            confirmButtonText: 'Fechar'
                        });
                    }
                });
            });

            $('.save').on('click', function (e) {
                e.preventDefault();

                $(this).html('Atualizando...').prop('disabled', true);

                let person_id = $('#person_id').val();

                $.ajax({
                    data: $('#person_form').serialize(),
                    url: "person/" + person_id,
                    type: "PUT",
                    dataType: 'json',
                    success: function (data) {
                        table.ajax.reload();

                        $('.save').html('Salvar').prop('disabled', false);
                        $('#ajaxModel').modal('hide');

                        Swal.fire({
                            title: 'Sucesso!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'Fechar'
                        });

                        handleMasks();
                    }
                });
            });
        });

    </script>
@endpush
