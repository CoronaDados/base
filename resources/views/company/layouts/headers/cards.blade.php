<div class="header bg-gradient-success pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-4 ">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                              <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">TODOS COLABORADORES</h5>
                                <span class="h2 font-weight-bold mb-0">{{$totalPersonsInCompany ?? 0}}</span>
                              </div>
                              <div class="col-auto">
                                <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                              </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                <span class="text-{{$percentPersonsInCompanyMonitoredToday < 100 ? 'danger' : 'success'}} mr-2">
                                    <strong>{{$percentPersonsInCompanyMonitoredToday ?? '0,00'}}%</strong>
                                </span>
                                <span class="text-nowrap">Monitorados Hoje: <strong>{{$totalPersonsInCompanyMonitoredToday ?? 0}}/{{$totalPersonsInCompany ?? 0}}</strong></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                              <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">MEUS COLABORADORES</h5>
                                <span class="h2 font-weight-bold mb-0">{{$totalMyPersons ?? 0}}</span>
                              </div>
                              <div class="col-auto">
                                <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                              </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                <span class="text-{{$percentMyPersonsMonitoredToday < 100 ? 'danger' : 'success'}} mr-2">
                                    <strong>{{$percentMyPersonsMonitoredToday ?? '0,00'}}%</strong>
                                </span>
                                <span class="text-nowrap">Monitorados Hoje: <strong>{{$totalMyPersonsMonitoredToday ?? 0}}/{{$totalMyPersons ?? 0}}</strong></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-4 ">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                              <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">CONFIRMADOS</h5>
                                <span class="h2 font-weight-bold mb-0">{{$totalCasesConfirmed ?? 0}}</span>
                              </div>
                              <div class="col-auto">
                                <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                    <i class="fas fa-users"></i>
                                </div>
                              </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                @if($totalCasesConfirmedToday)
                                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i>{{$percentCasesConfirmedToday ?? '0,00'}}%</span>
                                @endif
                                <span class="text-nowrap">Casos Hoje: <strong>{{$totalCasesConfirmedToday ?? 0}}</strong></span>
                            </p>
                        </div>                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
