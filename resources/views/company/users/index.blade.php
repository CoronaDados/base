@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    {{--    @include('layouts.headers.cards')--}}
    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card shadow">
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="mb-0">Usuários</h3>
                    </div>
                </div>
            </div>
            <div class="table-responsive p-2">
                <table class="table table-bordered data-table">
                    <thead>
                    <tr>
                        <th width="20px">No</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Função</th>
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
                <div class="modal-body">
                    <form id="UserForm" name="UserForm" class="form-horizontal">
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="name">Nome</label>
                                    <input type="text" id="name" name="name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="email">Email</label>
                                    <input type="text" id="email" name="email" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="phone">Telefone</label>
                                    <input type="text" id="phone" name="phone" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="cpf">CPF</label>
                                    <input type="text" id="cpf" name="cpf" class="form-control">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Função</label>
                                    <select class="form-control" name="role" id="role" >
                                        <option disabled selected>Atribua uma função</option>
                                        @foreach($roles as $role)
                                        <option value="{{$role}}">{{$role}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center">
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
                ajax: "{{ route('company.users.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'sector', name: 'sector'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('body').on('click', '.editUser', function (e) {
                e.preventDefault();
                let user_id = $(this).data('id');
                $.ajax({
                    url: "users/" + user_id,
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {

                        $('#modelHeading').html("Usuário " + data.user.name);
                        $('#saveBtn').val("edit-user");
                        $('#ajaxModel').modal('show');
                        $('#user_id').val(user_id);
                        $('#name').val(data.user.name);
                        $('#email').val(data.user.email);
                        $('#phone').val(data.user.phone);
                        $('#cpf').val(data.user.cpf);
                        if(data.user.roles.length > 0) {
                            $("#role option").removeAttr('selected')
                                .filter(`[value="${data.user.roles[0].name}"]`)
                                .attr('selected', true);
                        }else{
                            $("#role option").removeAttr('selected')
                                .filter(`[disabled]`)
                                .attr('selected', true);
                        }
                    },
                    error: function (data) {
                        alert('Erro ao carregar os dados, atualize a pagina.')
                    }
                });
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Atualizando..');
                var user_id = $('#user_id').val();
                $.ajax({
                    data: $('#UserForm').serialize(),
                    url: "users/" + user_id,
                    type: "PUT",
                    dataType: 'json',
                    success: function (data) {
                        $('.data-table').DataTable().ajax.reload();
                        $('#UserForm').trigger("reset");
                        $('#saveBtn').html('Salvar');
                        $('#ajaxModel').modal('hide');
                        Swal.fire({
                            title: 'Sucesso!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'Fechar'
                        })
                    },
                    error: function (data) {
                        $('#saveBtn').html('Gravar');
                        Swal.fire({
                            title: 'Ops!',
                            text: data.responseJSON.message,
                            icon: 'error',
                            confirmButtonText: 'Fechar'
                        })
                    }
                });
            });
        });

    </script>
@endpush
