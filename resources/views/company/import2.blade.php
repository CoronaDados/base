@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    <div class="container">
        <div class="card  mt-3">
            <div class="card-header">
                Importação de dados
            </div>
            <div class="card-body">
                <form action="{{ route('company.import2') }}" class="form-group" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="file" name="file" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <select class="form-control" required>
                                    <option disabled selected>Tipo da importação</option>
                                    <option value="lider">Lider</option>
                                    <option value="people">Colaboradores</option>
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
