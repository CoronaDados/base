@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')
{{--    @include('layouts.headers.cards')--}}
<div class="container-fluid pb-8 pt-3 pt-md-7">

    <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="align-items-center card-header d-flex justify-content-between collapsed monitoramento-titulo"
                id="headingOne" data-toggle="collapse" data-target="#collapseMonitoring" aria-expanded="false"
                aria-controls="collapseMonitoring">
                <h5 class="mb-0">Monitoramento diário</h5>
                <i class="ni ni-bold-down"></i>
            </div>
            <div id="collapseMonitoring" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                    <p><img width="100%" src="{{asset('img').'/informativo-01.jpeg'}}" /></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-xl-12 mb-5 mb-xl-0">
            <form id="multi-monitoring" method="POST">
                @csrf
                <div class="card shadow">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Colaboradores</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-header multiMoni">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3>Marque todos os colaboradores sem sintomas e clique no botão abaixo</h3>
                                <button type="submit" class="btn btn-primary" id="multiMoni">Sem sintomas</button>
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
                                        <th>Telefone</th>
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
                <h3 class="modal-title" id="modelHeading"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body pt-0">
                <form id="monitoringForm" name="monitoringForm" class="form-horizontal">
                    <input type="hidden" name="person_id" id="person_id">
                    <div class="row">

                        @foreach($validSymptoms as $symptom)
                        <div class="col-{{strlen($symptom->description) > 20 ? 'md-6' : 'md-3'}}">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" id="{{$symptom->value}}" name="symptoms[]"
                                        value="{{$symptom->value}}" class="check-symptoms custom-control-input">
                                    <label class="custom-control-label"
                                        for="{{$symptom->value}}">{{$symptom->description}}
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                    <div class="row">
                        <div class="col-md-12 p-0">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Alguma observação?</label>
                                <div class="col-sm-12">
                                    <textarea id="symptoms" rows="4" name="notes" placeholder="Alguma observação?"
                                        class="form-control form-control-alternative"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right p-0">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Salvar</button>
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
            let numpersons = $('#person_in_residence').val()
            if (numpersons > 0) {
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
                for (var i = 0; i < numpersons; i++) {
                    $('#show1').append(template)
                }

                $('#show1').show()
            } else {
                $('#show1').hide()
            }
        })

        $(function () {
            // ShowForm1();

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
                ajax: "{{ route($route) }}",
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
                    {data: 'person_id', name: 'person_id'},
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'sector', name: 'sector'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('body').on('click', '.editMonitoring', function () {
                $('#modelHeading').html("Monitorar " + $(this).data('name'));
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#person_id').val($(this).data('id'));
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();

                let person_id = $('#person_id').val(),
                    form = $('#monitoringForm'),
                    checkSymptoms = $('.check-symptoms');

                if($('.check-symptoms:checked').length > 0) {
                    $(this).html('Gravando..');

                    $.ajax({
                        data: form.serialize(),
                        url: "monitoring/" + person_id,
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            $('#saveBtn').html('Salvar');

                            form.trigger("reset");
                            $('#ajaxModel').modal('hide');

                            table.row($(this)).invalidate().draw();
                        },
                        error: function (data) {
                            $('#saveBtn').html('Gravar');
                        }
                    });
                } else {
                    checkSymptoms.toggleClass('is-invalid');

                    Swal.fire({
                        title: 'Ops!',
                        text: 'Parece que você esqueceu de selecionar o(s) sintoma(s).',
                        icon: 'warning',
                        confirmButtonText: 'Fechar',
                        onClose: () => {
                            checkSymptoms.toggleClass('is-invalid');
                        }
                    });
                }

            });

            $('#multiMoni').on('click', function(e) {
                e.preventDefault();

                let form = $('#multi-monitoring'),
                    rows_selected = table.column(0).checkboxes.selected();

                // Iterate over all selected checkboxes
                $.each(rows_selected, function(index, rowId){

                    // Create a hidden element
                    form.append(
                        $('<input>')
                            .attr('type', 'hidden')
                            .attr('name', 'id[]')
                            .attr('class','temp-input')
                            .val(rowId)
                    );
                });

                $.ajax({
                    data: form.serialize(),
                    url: '{{ route('company.multi.monitoring') }}',
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        $('.temp-input').remove();
                        table.column(0).checkboxes.deselect();
                        table.ajax.reload();
                    }
                });

            });
        });
</script>
@endpush
