@extends('layouts.app',['class' => 'bg-gradient-success'])

@section('content')
    @include('layouts.headers.cards')

    <div class="container-fluid mt--7">
        <div class="row mt-5">
            <div class="col-xl-6 mb-5 mb-xl-0">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Colaboradores Com COVID-19</h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Turno</th>
                                    <th scope="col">Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        Fulano de Tal
                                    </th>
                                    <td>
                                        Manhã
                                    </td>
                                    <td>
                                        19/03/2020
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        Beltrano de Tal
                                    </th>
                                    <td>
                                        Noite
                                    </td>
                                    <td>
                                        19/03/2020
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 mb-5 mb-xl-0">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Colaboradores Com Suspeita</h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Turno</th>
                                    <th scope="col">Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        Fulano de Tal
                                    </th>
                                    <td>
                                        Manhã
                                    </td>
                                    <td>
                                        19/03/2020
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        Beltrano de Tal
                                    </th>
                                    <td>
                                        Noite
                                    </td>
                                    <td>
                                        19/03/2020
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

{{--        @include('layouts.footers.auth')--}}
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
