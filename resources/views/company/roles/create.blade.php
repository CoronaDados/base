@extends('company.layouts.app',['class' => 'bg-gradient-success'])


@section('content')
    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card">
            <div class="card-header text-center">
                <h3>Criar função</h3>
            </div>
            <div class="card-header pull-right">
                <a class="btn btn-primary" href="{{ route('company.roles.index') }}"> Voltar</a>
            </div>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Ops!</strong> Há algum problema.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card-body">
                {!! Form::open(array('route' => 'company.roles.store','method'=>'POST')) !!}
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Name:</strong>
                            {!! Form::text('name', null, array('placeholder' => 'Nome','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <strong>Permissões:</strong>
                        <br/>
                        <br/>
                        <div class="row">
                            @foreach($permission as $value)
                                <div class="col-xs-4 col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                                            {{ $value->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
