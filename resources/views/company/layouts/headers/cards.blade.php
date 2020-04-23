<div class="pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->

            <div class="row justify-content-around">
                <div class="card-deck col-md-12 p-0">

                    <div class="card card-stats col-md-6 ml-0">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0">Monitoramento</h3>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center flex-column card-body">
                            <div class="row">
                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <h5 class="card-title text-uppercase text-muted mb-0">TODOS COLABORADORES</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $totalPersonsInCompany ?? 0 }}</span>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                <span
                                    class="text-{{ $percentPersonsInCompanyMonitoredToday < 100 ? 'danger' : 'success' }} mr-2">
                                    <strong>{{ $percentPersonsInCompanyMonitoredToday ?? '0,00' }}%</strong>
                                </span>
                                <span class="text-nowrap">Monitorados Hoje:
                                    <strong>{{ $totalPersonsInCompanyMonitoredToday ?? 0 }}/{{ $totalPersonsInCompany ?? 0 }}</strong></span>
                            </p>
                        </div>

                        <div class="d-flex justify-content-center flex-column card-body">
                            <div class="row">
                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <h5 class="card-title text-uppercase text-muted mb-0">MEUS COLABORADORES</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $totalMyPersons ?? 0 }}</span>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                <span
                                    class="text-{{ $percentMyPersonsMonitoredToday < 100 ? 'danger' : 'success' }} mr-2">
                                    <strong>{{ $percentMyPersonsMonitoredToday ?? '0,00' }}%</strong>
                                </span>
                                <span class="text-nowrap">Monitorados Hoje:
                                    <strong>{{ $totalMyPersonsMonitoredToday ?? 0 }}/{{ $totalMyPersons ?? 0 }}</strong></span>
                            </p>
                        </div>
                    </div>

                    <div class="card card-stats col-md-6 mr-0">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0">COVID-19</h3>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 d-flex justify-content-between">
                                    <h5 class="card-title text-uppercase text-muted">TOTAL DE CONFIRMADOS</h5>
                                    <span class="font-weight-bold">{{ $totalCasesConfirmed ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 d-flex justify-content-between">
                                    <h5 class="card-title text-uppercase text-muted">CONFIRMADOS ATIVOS</h5>
                                    <span class="font-weight-bold">{{ $totalCasesActivedConfirmed ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 d-flex justify-content-between">
                                    <h5 class="card-title text-uppercase text-muted">RECUPERADOS</h5>
                                    <span class="font-weight-bold">{{ $totalAllRecoveredCases ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 d-flex justify-content-between">
                                    <h5 class="card-title text-uppercase text-muted">SUSPEITOS</h5>
                                    <span class="font-weight-bold">{{ $totalSuspiciousCases ?? 0 }}</span>
                                </div>
                            </div>

{{--                            <div class="row">--}}
{{--                                <div class="col-sm-12 d-flex justify-content-between">--}}
{{--                                    <h5 class="card-title text-uppercase text-muted mb-0">ÓBITOS</h5>--}}
{{--                                    <span class="font-weight-bold">{{ $totalDeathCases ?? 0 }}</span>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <p class="my-3 text-sm d-flex justify-content-between">
                                <span class="text-nowrap">Casos Confirmados Hoje:
                                    <strong>{{ $totalCasesConfirmedToday ?? 0 }}</strong>
                                </span>
                                @if($percentCasesConfirmedToday !== '0,00')
                                    <span class="text-success">
                                        <i class="fa fa-arrow-up"></i>{{ $percentCasesConfirmedToday }}% desde ontem
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row mt-4">

                <div class="col-md-6 pl-0">
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
                            <table class="table align-items-center table-fixed">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Grupo de Risco</th>
                                    <th scope="col">Data</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($personsActivedConfirmedCases as $person)
                                        <tr>
                                            <td>{{ Helper::getFirstAndLastName($person->name) }}</td>
                                            <td>{{ $person->riskGroup }}</td>
                                            <td>{{ Helper::formatDateFromDB($person->date) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 pr-0">
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
                            <table class="table align-items-center table-fixed">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Grupo de Risco</th>
                                    <th scope="col">Data</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($personsSuspiciousCases as $person)
                                        <tr>
                                            <td>{{ Helper::getFirstAndLastName($person->name) }}</td>
                                            <td>{{ $person->riskGroup }}</td>
                                            <td>{{ Helper::formatDateFromDB($person->date) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 p-0">
                    <div class="card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Grupos de Risco</h3>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-fixed table-risk-group">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Grupo</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Suspeitos</th>
                                    <th scope="col">Negativo</th>
                                    <th scope="col">Positivos</th>
                                    <th scope="col">Recuperados</th>
                                    <th scope="col">Óbitos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riskGroups as $riskGroup)
                                <tr>
                                    <th scope="row">
                                        {{$riskGroup->name}}
                                    </th>
                                    <td class="text-center">
                                        {{$riskGroup->total_group}}
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                {{ Helper::getPercentValueAndFormat($riskGroup->total_suspect, $riskGroup->total_group) }}%
                                            </span>
                                            <div>
                                                <div class="progress w-100">
                                                    <div class="progress-bar bg-gradient-warning" role="progressbar"
                                                        aria-valuenow="{{ Helper::getPercentValueFromTotal($riskGroup->total_suspect, $riskGroup->total_group) }}"
                                                        aria-valuemin="0" aria-valuemax="100"
                                                        style="width: {{ Helper::getPercentValueFromTotal($riskGroup->total_suspect, $riskGroup->total_group) }}%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                {{ Helper::getPercentValueAndFormat($riskGroup->total_negative, $riskGroup->total_group) }}%
                                            </span>
                                            <div>
                                                <div class="progress w-100">
                                                    <div class="progress-bar bg-gradient-primary" role="progressbar"
                                                        aria-valuenow="{{ Helper::getPercentValueFromTotal($riskGroup->total_negative, $riskGroup->total_group) }}"
                                                        aria-valuemin="0" aria-valuemax="100"
                                                        style="width: {{ Helper::getPercentValueFromTotal($riskGroup->total_negative, $riskGroup->total_group) }}%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                {{ Helper::getPercentValueAndFormat($riskGroup->total_positive, $riskGroup->total_group) }}%
                                            </span>
                                            <div>
                                                <div class="progress w-100">
                                                    <div class="progress-bar bg-gradient-danger" role="progressbar"
                                                        aria-valuenow="{{ Helper::getPercentValueFromTotal($riskGroup->total_positive, $riskGroup->total_group) }}"
                                                        aria-valuemin="0" aria-valuemax="100"
                                                        style="width: {{ Helper::getPercentValueFromTotal($riskGroup->total_positive, $riskGroup->total_group) }}%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                {{ Helper::getPercentValueAndFormat($riskGroup->total_recover, $riskGroup->total_group) }}%
                                            </span>
                                            <div>
                                                <div class="progress w-100">
                                                    <div class="progress-bar bg-gradient-success" role="progressbar"
                                                        aria-valuenow="{{ Helper::getPercentValueFromTotal($riskGroup->total_recover, $riskGroup->total_group) }}"
                                                        aria-valuemin="0" aria-valuemax="100"
                                                        style="width: {{ Helper::getPercentValueFromTotal($riskGroup->total_recover, $riskGroup->total_group) }}%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span>
                                                {{ Helper::getPercentValueAndFormat($riskGroup->total_death, $riskGroup->total_group) }}%
                                            </span>
                                            <div>
                                                <div class="progress w-100">
                                                    <div class="progress-bar bg-dark" role="progressbar"
                                                        aria-valuenow="{{ Helper::getPercentValueFromTotal($riskGroup->total_death, $riskGroup->total_group) }}"
                                                        aria-valuemin="0" aria-valuemax="100"
                                                        style="width: {{ Helper::getPercentValueFromTotal($riskGroup->total_death, $riskGroup->total_group) }}%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
