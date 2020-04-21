@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')

    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card shadow">
            <div class="card-header">
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
                    <h3 class="modal-title" id="modelHeading"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="col-lg-12 pl-0 pt-0 pr-0">
                        <div class="nav-wrapper pt-0">
                            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-text" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-text-1-tab" data-toggle="tab" href="#tabs-text-1" role="tab" aria-controls="tabs-text-1" aria-selected="true">Visualização / Edição</a>
                                </li>
                                <li class="nav-item d-none">
                                    <a class="nav-link mb-sm-3 mb-md-0 historic" id="tabs-text-2-tab" data-toggle="tab" href="#tabs-text-2" role="tab" aria-controls="tabs-text-2" aria-selected="false">Histórico</a>
                                </li>
                                <li class="nav-item d-none">
                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-text-3-tab" data-toggle="tab" href="#tabs-text-3" role="tab" aria-controls="tabs-text-3" aria-selected="false">Diagnosticar</a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="tabs-text-1" role="tabpanel" aria-labelledby="tabs-text-1-tab">
                                @include('person.partials.form', ['isRequired' => false, 'route' => ''])
                            </div>


                                <div class="tab-pane fade historic-container" id="tabs-text-2" role="tabpanel" aria-labelledby="tabs-text-2-tab"></div>

                            <div class="tab-pane fade" id="tabs-text-3" role="tabpanel" aria-labelledby="tabs-text-3-tab">
                                @include('person.partials.diagnostic')
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

        $(function () {
            $.jMaskGlobals.watchDataMask = true;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let person_id = 0;

            $('body').on('click', '.editPerson', function (e) {
                e.preventDefault();

                person_id = $(this).data('id');
                $('.historic-container').empty();

                const navItem =  $('#ajaxModel .nav .nav-item'),
                        navItemFirst = navItem.first();
                navItemFirst.children().addClass(['active', 'show']).prop('aria-selected', true);
                navItemFirst.siblings().addClass('d-none');
                navItemFirst.siblings().children().removeClass(['active', 'show']).prop('aria-selected', false);

                const tabPane =  $('#ajaxModel .tab-content .tab-pane:first');
                tabPane.addClass(['active', 'show']);
                tabPane.siblings().removeClass(['active', 'show']);

                $.ajax({
                    url: 'person/' + person_id,
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {

                        let person = data.companyUser.person,
                            role = data.companyUser.roles[0].name,
                            historyTable = $('.history-table tbody');

                        historyTable.empty();

                        $('#modelHeading').html("Colaborador " + person.name);
                        $('#saveBtn').val("edit-user");
                        $('#ajaxModel').modal('show');

                        $('.person_id').val(person_id);
                        $('#name').val(person.name);
                        $('#email').val(data.companyUser.email);
                        $('#phone').val(person.phone).trigger('input');
                        $('#cpf').val(person.cpf).trigger('input');
                        $('#sector').val(person.sector);
                        $('#role').val(role);
                        $('#leader').val(data.leader);

                        if(person.birthday) {
                            $('#birthday').val(formattedDateFromDB(person.birthday))
                        }

                        const $radios = $('input:radio[name=gender]');
                        $radios.filter('[value=' + person.gender + ']').prop('checked', true);

                        const $checkboxes = $('.risk-groups').prop('checked', false);

                        if(person.risk_groups) {
                            for(risk_group of person.risk_groups) {
                                $checkboxes.filter('[value="' + risk_group.name + '"]').prop('checked', true);
                            }
                        }

                        $('.cep-person').val(person.cep).trigger('input');

                        // History
                        if(data.countMonitoringsPerson) {
                            navItem.removeClass('d-none');
                        }

                        // Diagnostic
                        const statusTest = $('input:radio[name=status_test]'),
                            statusCovid =  $('input:radio[name=status_covid]'),
                            textArea = $('textarea[name=notes]');

                        statusTest.prop('checked', false);
                        statusCovid.prop('checked', false);
                        textArea.val('');

                        if(data.cases) {
                            statusTest.filter('[value="' + data.cases.status_test + '"]').prop('checked', true);
                            statusCovid.filter('[value="' + data.cases.status_covid + '"]').prop('checked', true);

                            textArea.val(data.cases.notes);
                        }
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

            $('body').on('click', '.historic', function () {
                $.ajax({
                    url: '{{ url('monitoringPerson') }}/' + person_id,
                    type: "GET",
                    dataType: 'html',
                    success: function (data) {
                        $('#ajaxModel').modal('show');
                        $('.historic-container').html(data);
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

                let person_id = $('.person_id').val();

                if(isValid()) {
                    $(this).html('Atualizando...').prop('disabled', true);

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
                        },
                        error: function (e) {
                            $('#ajaxModel').modal('hide');
                            $('.save').html('Salvar').prop('disabled', false);

                            Swal.fire({
                                title: 'Erro!',
                                text: 'Erro ao atualizar os dados.',
                                icon: 'error',
                                confirmButtonText: 'Fechar'
                            });
                        }
                    });
                }
            });


        });

    </script>
@endpush
