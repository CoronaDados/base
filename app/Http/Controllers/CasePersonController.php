<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Model\Company\CompanyUser;
use App\Model\Person\CasePerson;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CasePersonController extends Controller
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
            $casePerson = CasePerson::with('person')->find($id);

            $statusFormatted = Helper::formatStatus($casePerson['status']);

            $case = new \stdClass();
            $case->symptoms = $statusFormatted[0];
            $case->obs = $statusFormatted[1];
            $case->person = $casePerson->person->name;
            $case->date = Helper::formatDateFromDB($casePerson['created_at']);

            return response()->json(compact('case'));
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
