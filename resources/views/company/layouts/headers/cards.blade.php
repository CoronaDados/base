<div class="pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->

            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-4">
                    <div class="card card-stats col-sm-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">TODOS COLABORADORES</h5>
                                    <span
                                        class="h2 font-weight-bold mb-0">{{ $totalPersonsInCompany ?? 0 }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
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
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-4">
                    <div class="card card-stats col-sm-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">MEUS COLABORADORES</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $totalMyPersons ?? 0 }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
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
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-4">
                    <div class="card card-stats col-sm-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">CONFIRMADOS</h5>
                                    <span
                                        class="h2 font-weight-bold mb-0">{{ $totalCasesConfirmed ?? 0 }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                @if($totalCasesConfirmedToday)
                                    <span class="text-success mr-2"><i
                                            class="fa fa-arrow-up"></i>{{ $percentCasesConfirmedToday ?? '0,00' }}%</span>
                                @endif
                                <span class="text-nowrap">Casos Hoje:
                                    <strong>{{ $totalCasesConfirmedToday ?? 0 }}</strong></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0">Grupos de Risco</h3>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
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
                                    @foreach([['name' => 'Não', 'count' => 1000],['name' => 'Gestante', 'count' => 200],['name' => 'Acima de 60 anos', 'count' => 400],['name' => 'Diabetes', 'count' => 500],['name' => 'Problemas Cardiovasculares', 'count' => 300],['name' => 'Problemas Respiratórios', 'count' => 100],['name' => 'Imunossuprimido', 'count' => 50],] as $riskGroup)
                                    <tr>
                                        <th scope="row">
                                            {{$riskGroup['name']}}
                                        </th>
                                        <td>
                                            {{$riskGroup['count']}}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="mr-2">60%</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-warning"
                                                            role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                                            aria-valuemax="100" style="width: 60%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="mr-2">60%</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-primary"
                                                            role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                                            aria-valuemax="100" style="width: 60%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="mr-2">60%</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-danger"
                                                            role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                                            aria-valuemax="100" style="width: 60%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="mr-2">60%</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-gradient-success"
                                                            role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                                            aria-valuemax="100" style="width: 60%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="mr-2">60%</span>
                                                <div>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-dark"
                                                            role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                                            aria-valuemax="100" style="width: 60%;"></div>
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
