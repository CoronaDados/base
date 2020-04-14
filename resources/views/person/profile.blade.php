@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')

    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card">
            <div class="card-header">
                <h3>Editar meus dados</h3>
            </div>

            <div class="card-body">
                @include('person.partials.form', ['isRequired' => true, 'route' => route('person.profile')])
            </div>
        </div>
    </div>
@endsection

