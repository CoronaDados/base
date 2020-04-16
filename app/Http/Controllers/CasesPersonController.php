<?php

namespace App\Http\Controllers;

use App\Model\Person\CasePerson;
use App\Model\Person\MonitoringPerson;
use App\Model\Person\Person;
use Illuminate\Http\Request;

class CasesPersonController extends Controller
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
     * @param Person $id
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request): void
    {


        $person = Person::find($request->person_id);
        dd($person->companyUsers()->id);

        $casesPerson = CasePerson::create(
            [
                'status_test' => $request->status_test,
                'status_covid' => $request->status_covid,
            ]
        );

        $person->casesPerson()->save($casesPerson);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
