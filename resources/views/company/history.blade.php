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
                                        <th>Caso Relatado</th>
                                        <th>Colaborador</th>
                                        <th>Sintomas</th>
                                        <th>Monitorado por</th>
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
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body pt-0">
                    <div class="col-lg-12 pl-0 pt-0 pr-0">
                        @include('company.partials.obsTable')
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
            columnDefs: [{
                targets: 0
            }],
            select: {
                style: 'multi',
            },
            order: [[1, 'asc']],
            columns: [
                {data: 'created_at', name: 'created_at'},
                {data: 'name', name: 'name'},
                {data: 'symptoms', name: 'symptoms'},
                {data: 'leader', name: 'leader'},
                {data: 'action', name: 'action'}
            ]
        });

        $('body').on('click', '.seeObs', function (e) {
            e.preventDefault();

            let monitoringPerson_id = $(this).data('id');

            $.ajax({
                url: '{{ url('monitoringPerson/') }}/' + monitoringPerson_id,
                type: "GET",
                dataType: 'json',
                success: function (data) {
                    $('#ajaxModel').modal('show');

                    let monitoringPerson = data.monitoring,
                        symptomsDiv = $('.symptoms'),
                        obs = $('.obs-monitoring');

                    symptomsDiv.empty();

                    $('#modelHeading').html("Mais informações do Colaborador " + monitoringPerson.person);

                    for(symptom of monitoringPerson.symptoms) {
                        $('<span>').addClass(['badge', 'badge-pill', 'badge-warning', 'mr-1', 'mb-1']).text(symptom).appendTo(symptomsDiv);
                    }

                    $('.date-monitoring').text('Caso relatado dia ' + monitoringPerson.date);


                    if(monitoringPerson.obs) {
                        obs.text(monitoringPerson.obs);
                    } else {
                        obs.text('Não foi cadastrado nenhuma observação.');
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
    });

    </script>
@endpush
