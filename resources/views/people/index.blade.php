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
                        <th>Email</th>
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
                <div class="modal-body">
                    @include('people.partials.form', ['isRequired' => false, 'route' => false, 'ajaxRoute' => route('people.index')])
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

            $('body').on('click', '.editPerson', function (e) {
                e.preventDefault();

                let person_id = $(this).data('id'),
                    SPMaskBehavior = function (val) {
                        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                    },
                    spOptions = {
                        onKeyPress: function(val, e, field, options) {
                            field.mask(SPMaskBehavior.apply({}, arguments), options);
                        }
                    };

                $.ajax({
                    url: 'people/' + person_id,
                    type: "GET",
                    dataType: 'json',
                    success: function (data) {
                        $('#modelHeading').html("Colaborador " + data.person.name);
                        $('#saveBtn').val("edit-user");
                        $('#ajaxModel').modal('show');
                        $('#user_id').val(person_id);
                        $('#name').val(data.person.name);
                        $('#email').val(data.person.email);
                        $('#phone').val(data.person.phone);
                        $('#cpf').val(data.person.cpf);

                        handleMasks();
                    },
                    error: function (data) {
                        alert('Erro ao carregar os dados, atualize a pagina.')
                    }
                });
            });
        });

    </script>
@endpush
