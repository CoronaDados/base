<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Model\Person\MonitoringPerson;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request)
    {
        if ($request->ajax()) {
            $monitoringPerson = MonitoringPerson::with('person')->find($id);

            $symptomsFormatted = Helper::formatSymptoms($monitoringPerson['symptoms']);

            $monitoring = new \stdClass();
            $monitoring->symptoms = $symptomsFormatted[0];
            $monitoring->obs = $symptomsFormatted[1];
            $monitoring->person = $monitoringPerson->person->name;
            $monitoring->date = Helper::formatDateFromDB($monitoringPerson['created_at']);

            return response()->json(compact('monitoring'));
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
