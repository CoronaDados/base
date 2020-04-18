<?php

namespace App\Http\Controllers\Company;

use App\Enums\StatusCovidTestType;
use App\Enums\StatusCovidType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Model\Company\Company;
use App\Model\Person\MonitoringPerson;
use App\Model\Person\Person;
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

        $totalCasesConfirmed = 0;
        $totalCasesConfirmedToday = 0;
        $percentCasesConfirmedToday = 0;


        return view('company.dashboard', compact([
            'totalPersonsInCompany',
            'totalPersonsInCompanyMonitoredToday',
            'percentPersonsInCompanyMonitoredToday',
            'totalMyPersons',
            'totalMyPersonsMonitoredToday',
            'percentMyPersonsMonitoredToday',
            'totalCasesConfirmed',
            'totalCasesConfirmedToday',
            'percentCasesConfirmedToday'
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
                $options = ['byDay'];
                $datas =  auth('company')->user()->monitoringsPerson($options);
            } else {
                $options = ['byLeader', 'byDay'];
                $datas =  auth('company')->user()->monitoringsPerson($options);
            }

            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-name="' . $row->name . ' <br>Peça para enviar uma mensagem no whatsapp com esse código: <strong>' . Helper::getPersonCode($row->person_id) . '</strong>" data-id="' . $row->person_id . '" data-original-title="Monitorar" class="edit btn btn-primary btn-sm editMonitoring">Monitorar</a>';

                    return $btn;
                })
                ->editColumn('name', function ($person) {
                    return Helper::getFirstAndLastName($person->name);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('company.monitoring', compact('route'));
    }

    public function monitoringHistory(Request $request)
    {
        if ($request->ajax()) {

            if (auth()->user()->hasRole('Admin')) {
                $options = ['getHistory'];
                $monitoringsPersons = auth('company')->user()->monitoringsPerson($options);
            } else {
                $options = ['getHistory', 'byLeader'];
                $monitoringsPersons = auth('company')->user()->monitoringsPerson($options);
            }

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
                ->editColumn('leader', function ($leader) {

                    $nameLeader = Helper::getFirstAndLastName($leader->leader);
                    $dateMonitoring = Helper::formatDateFromDB($leader->created_at);

                    return $nameLeader . '<small class="d-flex">' . $dateMonitoring . '</small>';
                })
                ->editColumn('medic', function ($leader) {

                    $nameMedic = Helper::getFirstAndLastName($leader->medic);
                    $dateDiagnostic = Helper::formatDateFromDB($leader->diagnostic_date);

                    return $nameMedic . '<small class="d-flex">' . $dateDiagnostic . '</small>';
                })
                ->editColumn('symptoms', function ($symptoms) {

                    $formattedSymptoms = Helper::formatSymptoms($symptoms->symptoms)[0];

                    if ($formattedSymptoms) {
                        $allSymptoms = '<ul class="mb-0">';
                        foreach ($formattedSymptoms as $symptom) {
                            $allSymptoms .= '<li>' . $symptom . '</li>';
                        }
                        $allSymptoms .= '</ul>';

                        return $allSymptoms;
                    } else {
                        $obs = (array) json_decode($symptoms->symptoms);

                        return $obs['obs'];
                    }
                })
                ->rawColumns(['symptoms', 'action', 'leader', 'medic'])
                ->make(true);
        }

        $tests = StatusCovidTestType::getValues();
        $status = StatusCovidType::getValues();

        return view('company.history', compact('tests', 'status'));
    }

    public function storeMonitoring($id, Request $request)
    {
        $person = Person::find($id);
        $monitoring = new MonitoringPerson(['symptoms' => json_encode($request->all())]);
        $person->createMonitoringPersonDay()->save($monitoring);

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
        $persons = Person::whereIn('id', $request->id)->get();

        foreach ($persons as $person) {
            $monitoring = new MonitoringPerson(['symptoms' => '{"obs":"Sem Sintomas"}']);
            $person->createMonitoringPersonDay()->save($monitoring);
        }

        flash('Atualizado com sucesso', 'info');

        return redirect(route('company.monitoring'));
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
