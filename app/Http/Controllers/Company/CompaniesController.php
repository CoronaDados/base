<?php

namespace App\Http\Controllers\Company;

use App\Enums\RiskGroupType;
use App\Enums\StatusCovidTestType;
use App\Enums\StatusCovidType;
use App\Enums\SymptomsType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Model\Company\Company;
use App\Model\Person\CasePerson;
use App\Model\Person\MonitoringPerson;
use App\Model\Person\Person;
use App\Model\Person\RiskGroupPerson;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;

class CompaniesController extends Controller
{
    public function dashboard()
    {
        $currentUser = auth('company')->user();

        $totalPersonsInCompany = $currentUser->countPersons();
        $totalPersonsInCompanyMonitoredToday = $currentUser->countPersonsInCompanyMonitoredToday();
        $percentPersonsInCompanyMonitoredToday = Helper::getPercentFormatted(Helper::getPercentValueFromTotal($totalPersonsInCompanyMonitoredToday, $totalPersonsInCompany));

        $totalMyPersons = $currentUser->persons()->count();
        $totalMyPersonsMonitoredToday = $currentUser->countMyPersonsMonitoredToday();
        $percentMyPersonsMonitoredToday = Helper::getPercentFormatted(Helper::getPercentValueFromTotal($totalMyPersonsMonitoredToday, $totalMyPersons));

        $totalCasesConfirmed = $currentUser->countAllConfirmedCases();
        $totalCasesActivedConfirmed = $currentUser->countActivedConfirmedCases();

        $totalCasesConfirmedToday =  $currentUser->countConfirmedCasesToday();
        $totalCasesConfirmedYesterday =  $currentUser->countConfirmedCasesYesterday();
        $percentCasesConfirmedToday = Helper::getPercentFormatted(Helper::getPercentValueFromTotal($totalCasesConfirmedToday, $totalCasesConfirmedYesterday));

        $totalSuspiciousCases =  $currentUser->countSuspiciousCases();
        $totalAllRecoveredCases =  $currentUser->countAllRecoveredCases();
        $totalDeathCases = $currentUser->countDeathCases();

        $riskGroups = $currentUser->company->getCountsDashboardRiskGroups();
        $personsActivedConfirmedCases = $currentUser->personsActivedConfirmedCases();
        $personsSuspiciousCases = $currentUser->personsSuspiciousCases();

        return view('company.dashboard', compact([
            'totalPersonsInCompany',
            'totalPersonsInCompanyMonitoredToday',
            'percentPersonsInCompanyMonitoredToday',
            'totalMyPersons',
            'totalMyPersonsMonitoredToday',
            'percentMyPersonsMonitoredToday',
            'totalCasesConfirmed',
            'totalCasesActivedConfirmed',
            'totalCasesConfirmedToday',
            'totalCasesConfirmedYesterday',
            'percentCasesConfirmedToday',
            'totalAllRecoveredCases',
            'totalSuspiciousCases',
            'totalDeathCases',
            'personsActivedConfirmedCases',
            'personsSuspiciousCases',
            'riskGroups'
        ]));
    }

    public function tips()
    {
        return view('company.tips');
    }

    public function help()
    {
        return view('company.help');
    }

    public function monitoring(Request $request)
    {
        $route = Route::currentRouteName();

        if ($request->ajax()) {
            if ($request->route()->getName() === 'company.monitoringAll') {
                $options = [];
            } else {
                $options = ['byLeader'];
            }

            $datas =  auth('company')->user()->monitoringPersons($options);

            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-name="' . $row->name . ' <br/>
                                Peça para enviar um <strong>Oi</strong> pelo Whatsapp ao número (48) 99802-3637" data-id="' . $row->person_id . '"
                                data-original-title="Monitorar" class="edit btn btn-primary btn-sm editMonitoring">Monitorar</a>';

                    return $btn;
                })
                ->editColumn('name', function ($person) {

                    $nameFormatted = Helper::getFirstAndLastName($person->name);

                    $title = $nameFormatted . ' já iniciou a conversa no Whatsapp!';
                    $color = 'text-success';

                    if(!$person->bot_optin) {
                        $title = $nameFormatted . ' não iniciou a conversa no Whatsapp!';
                        $color = 'text-danger';
                    }

                    $whatsapp = '<span class="h2 font-weight-normal m-0">
                                    <i class="' . $color . ' fab fa-whatsapp"></i>
                                </span>';

                    $div = '<div class="d-flex justify-content-between align-items-center whatsapp" rel="tooltip" data-toggle="tooltip"
                                data-placement="top" title="' . $title . '">' .
                                $nameFormatted . $whatsapp .
                            '</div>';

                    return $div;
                })
                ->editColumn('phone', function ($person) {
                    return Helper::formatPhone($person->phone);
                })
                ->rawColumns(['action', 'name'])
                ->make(true);
        }

        $validSymptoms = SymptomsType::getInstances();

        return view('company.monitoring', compact('route', 'validSymptoms'));
    }

    public function monitoringHistory(Request $request)
    {
        if ($request->ajax()) {

            if (auth()->user()->hasRole('Admin')) {
                $options = [];
            } else {
                $options = ['byLeader'];
            }

            $monitoringsPersons = auth('company')->user()->monitoringsHistoryPerson($options);

            return DataTables::of($monitoringsPersons)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $buttons = '<button type="button" rel="tooltip" class="table-action btn btn-info btn-icon btn-sm see-details" data-name="' . $row->name . '" data-id="' . $row->person_id . '"
                                    data-toggle="tooltip" data-placement="top" title="Detalhes" data-original-title="Detalhes">
                                    <i class="fas fa-address-card"></i>
                                </button>';

                    $buttons .= '<button type="button" rel="tooltip" class="table-action btn btn-success btn-icon btn-sm btn-simple set-diagnostic" data-name="' . $row->name . '" data-id="' . $row->person_id . '"
                                    data-toggle="tooltip" data-placement="top" title="Diagnosticar" data-original-title="Diagnosticar">
                                    <i class="fas fa-user-md"></i>
                                </button>';

                    return $buttons;
                })
                ->editColumn('name', function ($user) {
                    return Helper::getFirstAndLastName($user->name);
                })
                ->editColumn('dateMonitoring', function ($date) {
                    return Helper::formatDateTimeFromDB($date->dateMonitoring);
                })
                ->editColumn('symptoms', function ($user) {

                    $symptoms = json_decode($user->symptoms) ?? null;

                    if (!isset($symptoms->monitored)) {
                        return 'Sem sintomas';
                    }

                    $allSymptoms = '<ul class="mb-0 pl-1">';
                    foreach ($symptoms->monitored as $symptom) {
                        $allSymptoms .= '<li>' . SymptomsType::getDescription($symptom) . '</li>';
                    }
                    $allSymptoms .= '</ul>';

                    return $allSymptoms;
                })
                ->editColumn('status_covid', function ($case) {
                    return $case->status_covid ?? 'Não foi diagnosticado';
                })
                ->rawColumns(['symptoms', 'action'])
                ->make(true);
        }

        $tests = StatusCovidTestType::getValues();
        $status = StatusCovidType::getValues();

        return view('company.history', compact('tests', 'status'));
    }

    public function storeMonitoring($id, Request $request)
    {
        $person = Person::find($id);
        $symptoms = json_encode(['monitored' => $request->symptoms]);
        $person->monitoringsPerson()->create(['symptoms' => $symptoms, 'notes' => $request->notes]);

        return true;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function multiMonitoring(Request $request)
    {
        if ($request->has('id')) {

            $persons = Person::whereIn('id', $request->id)->get();

            foreach ($persons as $person) {
                $person->monitoringsPerson()->create(['symptoms' => null, 'notes' => 'Sem Sintomas']);
            }

            flash('Atualizado com sucesso', 'info');

            return redirect(route('company.monitoring'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Company\Company  $companies
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $companies)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Company\Company  $companies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $companies)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Company\Company  $companies
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $companies)
    {
        //
    }
}
