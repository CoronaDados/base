@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card  mt-3">
            <div class="card-header">
                Importação de admin, líderes e colaboradores

                <div class="card-header">
                    <a class="btn btn-primary" href="{{route('company.files', 'modelo_person.xlsx')}}">Download do modelo</a>
                </div>
            </div>
            <div class="card-body">
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
