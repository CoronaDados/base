@extends('company.layouts.app',['class' => 'bg-gradient-success'])


@section('content')
    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card">
            <div class="card-header text-center">
                <h3>Gerenciamento de funcões</h3>
            </div>
            <div class="card-header pull-right">
                @can('Cadastrar Funções')
                    <a class="btn btn-success" href="{{ route('company.roles.create') }}"> Criar nova Função</a>
                @endcan
            </div>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th width="280px">Action</th>
                    </tr>
                    @foreach ($roles as $key => $role)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <a class="btn btn-info" href="{{ route('company.roles.show',$role->id) }}">Ver</a>
                                @can('Editar Funções')
                                    <a class="btn btn-primary"
                                       href="{{ route('company.roles.edit',$role->id) }}">Editar</a>
                                @endcan
                                @can('Deletar Funções')
                                    {!! Form::open(['method' => 'DELETE','route' => ['company.roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Apagar', ['class' => 'btn btn-danger']) !!}
                                    {!! Form::close() !!}
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </table>
                {!! $roles->render() !!}
            </div>
        </div>
    </div>
@endsection
