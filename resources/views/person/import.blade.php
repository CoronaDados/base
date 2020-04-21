@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="m-0">Importação</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="card-deck col-md-12 pr-0">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto d-flex align-items-center">
                                        <div class="icon icon-shape bg-gradient-primary text-white rounded-circle mb-0 mr-4">
                                            <strong>1</strong>
                                        </div>
                                        <h4 class="h3 text-primary text-uppercase mb-0">Admin</h4>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    Admins são pessoas que terão acesso total ao sistema.
                                </p>
                            </div>
                        </div>

                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto d-flex align-items-center">
                                        <div class="icon icon-shape bg-gradient-success text-white rounded-circle mb-0 mr-4">
                                            <strong>2</strong>
                                        </div>
                                        <h4 class="h3 text-success text-uppercase mb-0">Líderes</h4>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    Líderes são pessoas responsáveis que irão monitorar seus liderados.
                                </p>
                            </div>
                        </div>

                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto d-flex align-items-center">
                                        <div class="icon icon-shape bg-gradient-warning text-white rounded-circle mb-0 mr-4">
                                            <strong>3</strong>
                                        </div>
                                        <h4 class="h3 text-warning text-uppercase mb-0">Colaboradores</h4>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    Colaboradores são pessoas que serão monitoradas. Podem ser colaboradores da empresa, pessoas do município, etc. Elas não terão acesso ao sistema.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <br>

                <div class="card card-frame">
                    <div class="card-body">
                        <span class="alert-icon"><i class="ni ni-cloud-download-95"></i></span>
                        <span class="alert-text"><a href="{{route('company.files', 'modelo_person.xlsx')}}">Clique aqui</a> para baixar o modelo de importação dos dados (XLS).</span>
                    </div>
                </div>

                <br>

                <form action="{{ route('person.import') }}" class="form-group" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="file" name="file" class="form-control form-control-alternative" accept="xls, csvcsv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <select name="role" class="form-control form-control-alternative" required>
                                    <option value="" disabled selected>Tipo de usuário</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->name}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-success">Importar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
