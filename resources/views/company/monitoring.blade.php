@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    {{--    @include('layouts.headers.cards')--}}
    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Monitoramento diario</h5>
                <img src="{{asset('img').'/informativo-01.jpeg'}}" />
            </div>
        </div>
        <div>

        </div>

        <div class="row mt-5">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <form id="multi-monitoring" action="{{route('company.person.multi.monitoring')}}" method="POST">
                    @csrf
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Colaboradores</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-header multiMoni">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3>Ações em massa</h3>
                                <button type="submit" class="btn btn-primary" id="multiMoni">Monitorar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive p-2">
                            <!-- Projects table -->

                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th width="20px">No</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Setor</th>
                                    <th width="100px">
                                        <Ações></Ações>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
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
                <div class="modal-body">
                    <form id="CustomerForm" name="CustomerForm" class="form-horizontal">
                        <input type="hidden" name="person_id" id="person_id">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Febre?</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="febre-sim" name="febre" value="sim" class="custom-control-input">
                                        <label class="custom-control-label" for="febre-sim">Sim</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="febre-nao" name="febre" checked  value="nao" class="custom-control-input">
                                        <label class="custom-control-label" for="febre-nao">Não</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Dor no corpo?</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="dor-sim" name="dor" value="sim" class="custom-control-input">
                                        <label class="custom-control-label" for="dor-sim">Sim</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="dor-nao" name="dor" checked value="nao" class="custom-control-input">
                                        <label class="custom-control-label" for="dor-nao">Não</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Coriza?</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="coriza-sim" name="coriza" value="sim" class="custom-control-input">
                                        <label class="custom-control-label" for="coriza-sim">Sim</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="coriza-nao" checked name="coriza"  value="nao" class="custom-control-input">
                                        <label class="custom-control-label" for="coriza-nao">Não</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tose?</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="tose-sim" name="tose" value="sim" class="custom-control-input">
                                        <label class="custom-control-label" for="tose-sim">Sim</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="tose-nao" checked name="tose"  value="nao" class="custom-control-input">
                                        <label class="custom-control-label"  for="tose-nao">Não</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Alguma observação?</label>
                                    <div class="col-sm-12">
                                <textarea id="status" rows="4" name="obs" required="" placeholder="Alguma observação?"
                                          class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Gravar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                ajax: "{{ route('company.monitoring') }}",
                columnDefs: [{
                    targets: 0,
                    checkboxes: {
                        selectRow: true
                    }
                }],
                select: {
                    style: 'multi',
                },
                order: [[1, 'asc']],
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'sector', name: 'sector'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('body').on('click', '.editMonitoring', function () {
                //$.get("" +'/' + Customer_id +'/edit', function (data) {
                $('#modelHeading').html("Monitorar " + $(this).data('name'));
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#person_id').val($(this).data('id'));
                //  $('#name').val(data.name);
                //  $('#detail').val(data.detail);
                //  })
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Gravando..');
                var person_id = $('#person_id').val();
                $.ajax({
                    data: $('#CustomerForm').serialize(),
                    url: "monitoring/" + person_id,
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {

                        $('#CustomerForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.row($(this)).invalidate().draw();

                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Gravar');
                    }
                });
            });
            $('#multi-monitoring').on('submit', function(e){
                var form = this;

                var rows_selected = table.column(0).checkboxes.selected();

                // Iterate over all selected checkboxes
                $.each(rows_selected, function(index, rowId){
                    // Create a hidden element
                    $(form).append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'id[]')
                            .val(rowId)
                    );
                });
            });
        });

    </script>
@endpush
