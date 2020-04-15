<?php

namespace App\Http\Controllers\Company;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Model\Company\Company;
use App\Model\Person\CasePerson;
use App\Model\Person\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CompaniesController extends Controller
{
    public function dashboard()
    {
        $personsCompany = auth('company')->user()->countPersons();
        $personsUser =   auth('company')->user()->persons()->count();

        return view('company.dashboard', compact(['personsCompany', 'personsUser']));
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
        if ($request->ajax()) {
            if (auth('company')->user()->can('Ver Usuários')) {
                if (auth()->user()->hasRole('Admin')) {
                    $options = ['byDay'];
                    $datas =  auth('company')->user()->casesPerson($options);
                } else {
                    $options = ['byLeader', 'byDay'];
                    $datas =  auth('company')->user()->casesPerson($options);
                }
            }

            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-name="' . $row->name . ' <br>Peça para enviar uma mensagem no whatsapp com esse código: <strong>' . Helper::getPersonCode($row->person_id) . '</strong>" data-id="' . $row->person_id . '" data-original-title="Monitorar" class="edit btn btn-primary btn-sm editMonitoring">Monitorar</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('company.monitoring');

    }

    public function monitoringHistory(Request $request)
    {
        if ($request->ajax()) {

            if (auth()->user()->hasRole('Admin')) {
                $options = ['getHistory'];
                $casesPersons = auth('company')->user()->casesPerson($options);
            } else {
                $options = ['getHistory', 'byLeader'];
                $casesPersons = auth('company')->user()->casesPerson($options);
            }

            return DataTables::of($casesPersons)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-name="' . $row->name . '" data-id="' . $row->id . '" data-original-title="Observações" class="edit btn btn-primary btn-sm seeObs">Observações</a>';
                        return $btn;
                    })
                    ->editColumn('created_at', function ($date) {
                        return Helper::formatDateFromDB($date->created_at);
                    })
                    ->editColumn('name', function ($user) {
                        return Helper::getFirstAndLastName($user->name);
                    })
                    ->editColumn('leader', function ($leader) {
                        return Helper::getFirstAndLastName($leader->name);
                    })
                    ->editColumn('status', function ($status) {

                        $formattedStatus = Helper::formatStatus($status->status)[0];

                        if($formattedStatus) {
                            $allSymptoms = '<ul class="mb-0">';
                            foreach ($formattedStatus as $symptom) {
                                $allSymptoms .= '<li>' . $symptom . '</li>';
                            }
                            $allSymptoms .= '</ul>';

                            return $allSymptoms;
                        } else {
                            $obs = (array) json_decode($status->status);

                            return $obs['obs'];

                        }



                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
        }

        return view('company.history');
    }

    public function storeMonitoring($id, Request $request)
    {
        $person = Person::find($id);
        $monitoring = new CasePerson(['status' => json_encode($request->all())]);
        $person->createCasePersonDay()->save($monitoring);

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
            $monitoring = new CasePerson(['status' => '{"obs":"Sem Sintomas"}']);
            $person->createCasePersonDay()->save($monitoring);
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
