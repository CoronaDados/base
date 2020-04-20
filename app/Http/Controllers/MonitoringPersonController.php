<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Model\Company\CompanyUser;
use App\Model\Person\MonitoringPerson;
use App\Model\Person\Person;
use Illuminate\Http\Request;

class MonitoringPersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id, Request $request)
    {
        if ($request->ajax()) {
            $person = Person::with('monitoringsPerson', 'casesPerson')->find($id);
            $personName = $person->name;

            $monitoringsPerson = $person->monitoringsPerson;
            foreach ($monitoringsPerson as $monitoring) {
                $object = new \stdClass();
                $symptomsFormatted = Helper::formatSymptoms($monitoring->symptoms);
                $object->symptoms = $symptomsFormatted[0];
                $object->date = Helper::formatDateFromDB($monitoring->created_at);
                $object->obs = $symptomsFormatted[1];

                $user = CompanyUser::find($monitoring->user_id);
                $object->monitoredBy = $user->person->name;

                $monitorings[] = $object;
            }

            $casesPerson = $person->casesPerson;
            foreach ($casesPerson as $case) {
                $caseObject = new \stdClass();
                $caseObject->status_covid = $case->status_covid;
                $caseObject->status_test = $case->status_test;
                $caseObject->date = Helper::formatDateFromDB($case->created_at);
                $caseObject->notes = $case->notes;

                $user = CompanyUser::find($case->user_id);
                $caseObject->diagnosedBy = $user->person->name;

                $cases[] = $caseObject;
            }

            return view('company.partials.details', compact('monitorings', 'cases', 'personName'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
