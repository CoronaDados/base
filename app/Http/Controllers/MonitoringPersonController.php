<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
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

            $monitoringsPerson = $person->monitoringsPerson;
            $personName = $person->name;
            foreach ($monitoringsPerson as $monitoring) {
                $object = new \stdClass();
                $symptomsFormatted = Helper::formatSymptoms($monitoring->symptoms);
                $object->symptoms = $symptomsFormatted[0];
                $object->date = Helper::formatDateFromDB($monitoring->created_at);
                $object->obs = $symptomsFormatted[1];

                $monitorings[] = $object;
            }

            $casesPerson = $person->casesPerson;
            foreach ($casesPerson as $case) {
                $caseDbject = new \stdClass();
                $caseDbject->status_covid = $case->status_covid;
                $caseDbject->status_test = $case->status_test;
                $caseDbject->date = Helper::formatDateFromDB($case->created_at);
                $caseDbject->notes = $case->notes;

                $cases[] = $caseDbject;
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
