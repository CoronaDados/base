@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    <div class="container-fluid pb-8 pt-3 pt-md-7">

        <div class="row">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <form id="multi-monitoring" action="{{route('company.monitoring')}}" method="POST">
                    @csrf
                <div class="card shadow">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Histórico de Sintomas</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive p-2">
                            <table class="table table-bordered data-table align-items-center">
                                <thead>
                                    <tr>
                                        <th>Colaborador</th>
                                        <th>Monitorado em</th>
                                        <th>Sintomas</th>
                                        <th>Diagnóstico</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </form>
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
                    <div class="col-lg-12 pl-0 pt-0 pr-0 details-container"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ajaxModelDiagnostic" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modelHeadingDiagnostic"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="col-lg-12 pl-0 pt-0 pr-0">
                        @include('person.partials.diagnostic')
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@push('js')
    <script>
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
        serverSide: true,
        ajax: "{{ route('company.monitoring.history') }}",
        columnDefs: [
            {
                targets: 0
            },
            {
                targets: 3,
                className: 'text-center'
            }
        ],
        select: {
            style: 'multi',
        },
        order: [[1, 'desc']],
        columns: [
            {data: 'name', name: 'name'},
            {data: 'dateMonitoring', name: 'dateMonitoring'},
            {data: 'symptoms', name: 'symptoms'},
            {data: 'status_covid', name: 'status_covid'},
            {data: 'action', name: 'action'}
        ]
    });

    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.table-action').tooltip();

        $('body').on('click', '.see-details', function (e) {
            e.preventDefault();

            let monitoringPerson_id = $(this).data('id');

            $.ajax({
                url: '{{ url('monitoringPerson') }}/' + monitoringPerson_id,
                type: "GET",
                dataType: 'html',
                success: function (data) {
                    $('#ajaxModel').modal('show');
                    $('.details-container').html(data);
                    $('#modelHeading').html("Mais informações do Colaborador " + $('.details').data('person-name'));
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

        $('body').on('click', '.set-diagnostic', function (e) {
            e.preventDefault();

            let person_id = $(this).data('id');
            $('.person_id').val(person_id);

            $.ajax({
                url: '{{ url('casesPerson/') }}/' + person_id,
                type: "GET",
                dataType: 'json',
                success: function (data) {
                    $('#ajaxModelDiagnostic').modal('show');
                    $('#modelHeadingDiagnostic').html("Diagnóstico do Colaborador " + data.cases.person);

                    const statusTest = $('input:radio[name=status_test]'),
                        statusCovid =  $('input:radio[name=status_covid]'),
                        textArea = $('textarea[name=notes]');

                    statusTest.prop('checked', false);
                    statusCovid.prop('checked', false);
                    textArea.val('');

                    statusTest.filter('[value="' + data.cases.status_test + '"]').prop('checked', true);
                    statusCovid.filter('[value="' + data.cases.status_covid + '"]').prop('checked', true);

                    textArea.val(data.cases.notes);
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
    });

    </script>
@endpush
