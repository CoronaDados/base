@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    <div class="container-fluid pb-8 pt-3 pt-md-7">

        <div class="row">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <form id="multi-monitoring" action="{{route('company.monitoring')}}" method="POST">
                    @csrf
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Histórico de sintomas</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive p-2">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Nome</th>
                                        <th>Sintomas</th>
                                        <th>Monitorado por</th>
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
                    "sNext": "&raquo;",
                    "sPrevious": "&laquo;",
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
                {data: 'person.name', name: 'person.name'},
                {data: 'status_format', name: 'status_format'},
                {data: 'leader.name', name: 'leader.name', orderable: true, searchable: true},
            ]
        });
    });

    </script>
@endpush
