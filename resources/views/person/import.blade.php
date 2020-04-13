@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card  mt-3">
            <div class="card-header">
                <h3>Importação de Colaboradores</h3>
            </div>
            <div class="card-body">
                <div class="row mb-12">

                    <div class="col-md-4">
                        <span class="badge badge-circle bg-primary text-white">1</span>
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <span class="h2 font-weight-bold mb-0">Importar Admin</span>
                                    <p>
                                        <span>Admins são pessoas que terão acesso total ao sistema.</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <span class="badge badge-circle bg-primary text-white">2</span>
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <span class="h2 font-weight-bold mb-0">Importar Líderes</span>
                                    <p>
                                        <span>Líderes são pessoas responsáveis que irão monitorar seus liderados.</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <span class="badge badge-circle bg-primary text-white">3</span>
                        <div class="card card-stats">
                            <!-- Card body -->
                            <div class="card-body">
                                <div class="row">
                                    <span class="h2 font-weight-bold mb-0">Importar Colaboradores</span>
                                    <p>
                                        <span>Colaboradores são pessoas que serão monitoradas. Podem ser colaboradores da empresa, pessoas do município, etc. Elas não terão acesso ao sistema.</span>
                                    </p>
                                </div>
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
                                <input type="file" name="file" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <select name="role" class="form-control" required>
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
