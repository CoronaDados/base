<div class="col-lg-12 pl-0 pt-0 pr-0 details" data-person-name="{{ $personName }}">
    @isset($cases)
        <div class="nav-wrapper pt-0">
            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-text" role="tablist">
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0 active" id="monitoramento-tab" data-toggle="tab" href="#monitoramento-text" role="tab" aria-controls="monitoramento-text" aria-selected="true">Histórico de Monitoramento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0" id="diagnostico-tab" data-toggle="tab" href="#diagnostico-text" role="tab" aria-controls="diagnostico-text" aria-selected="false">Histórico de Diagnóstico</a>
                </li>
            </ul>
        </div>
    @endisset

    @isset($monitorings)
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active show" id="monitoramento-text" role="tabpanel" aria-labelledby="monitoramento-tab">
                <h4>Histórico de Monitoramento</h4>
                <div class="timeline timeline-one-side">
                        @foreach($monitorings as $monitoring)
                            <div class="timeline-block">
                            <span class="timeline-step text-white bg-gradient-warning">
                                <i class="ni ni-calendar-grid-58"></i>
                            </span>

                                <div class="timeline-content">
                                    <small class="text-muted font-weight-bold date-monitoring">
                                        {{ $monitoring->date }} por {{ $monitoring->monitoredBy }}
                                    </small>

                                    <h5 class=" mt-3 mb-0">Observações</h5>
                                    <p class=" text-sm mt-1 mb-0 obs-monitoring">
                                        {{ $monitoring->obs ?? 'Não foi cadastrado nenhuma observação.'}}
                                    </p>

                                    <div class="mt-3 symptoms">
                                        <h5 class=" mt-3 mb-0">Sintomas</h5>
                                        @foreach($monitoring->symptoms as $symptom)
                                            <span class="badge badge-pill badge-warning mr-1 mb-1">
                                            {{ $symptom }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                </div>
            </div>
        @endisset

        @isset($cases)
            <div class="tab-pane fade" id="diagnostico-text" role="tabpanel" aria-labelledby="diagnostico-tab">
                <h4>Histórico de Diagnóstico</h4>
                <div class="timeline timeline-one-side">
                    @foreach($cases as $case)
                        <div class="timeline-block">
                        <span class="timeline-step text-white bg-gradient-info">
                             <i class="fas fa-user-md"></i>
                        </span>

                            <div class="timeline-content">
                                <small class="text-muted font-weight-bold date-monitoring">
                                    {{ $case->date }} por {{ $case->diagnosedBy }}
                                </small>

                                <h5 class=" mt-3 mb-0">Observações</h5>
                                <p class=" text-sm mt-1 mb-0 obs-monitoring">
                                    {{ $case->notes ?? 'Não foi cadastrado nenhuma observação.'}}
                                </p>

                                <div class="mt-3 symptoms">
                                    <h5 class=" mt-3 mb-0">Diagnóstico</h5>
                                    <span class="badge badge-pill badge-info mr-1 mb-1">
                                    {{ $case->status_covid }} PARA COVID-19
                                </span>
                                    <span class="badge badge-pill badge-info mr-1 mb-1">
                                    Teste {{ $case->status_test }}
                                </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endisset
    </div>
</div>
