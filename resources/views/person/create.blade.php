@extends('company.layouts.app',['class' => 'bg-gradient-success'])

@section('content')

    <div class="container-fluid pb-8 pt-3 pt-md-7">
        <div class="card">
            <div class="card-header">
                <h3 class="m-0">Cadastrar Colaborador</h3>
            </div>

            <div class="card-body">
                @include('person.partials.form', ['isRequired' => true, 'route' => route('person.store')])
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function () {
            $('#person_form').on('submit', function (e) {
                if(isValid()) {
                    return;
                }

                e.preventDefault();
            });
        });
    </script>
@endpush
