<?php

namespace App\Http\Controllers;

use App\Model\Person\CasePerson;
use App\Model\Person\Person;
use Illuminate\Http\JsonResponse;
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
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $personId = $request->person_id;
        $statusTest = $request->status_test;
        $statusCovid = $request->status_covid;
        $notes = $request->notes;

        $person = Person::find($personId);
        $oldCases = $person->casesPerson->first();

        if($oldCases) {
            if($statusTest !== $oldCases->status_test || $statusCovid !== $oldCases->status_covid) {
                $casesPerson = new CasePerson(
                    [
                        'status_test' => $statusTest,
                        'status_covid' => $statusCovid,
                    ]
                );

                $person->casesPerson()->save($casesPerson);
            } else if($notes) {
                $saveNote = CasePerson::find($oldCases->id);
                $saveNote->notes = $notes;
                $saveNote->save();
            }
        } else {
            $casesPerson = new CasePerson(
                [
                    'status_test' => $statusTest,
                    'status_covid' => $statusCovid,
                    'notes' => $notes
                ]
            );

            $person->casesPerson()->save($casesPerson);
        }

        flash('Diagnóstico cadastrado com sucesso!', 'info');

        return response()->json(['success' => true], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function show($id, Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $person = Person::with('casesPerson')->find($id);
            $casesPerson = $person->casesPerson->first();

            $cases = new \stdClass();
            $cases->status_covid = $casesPerson->status_covid ?? null;
            $cases->status_test = $casesPerson->status_test ?? null;
            $cases->notes = $casesPerson->notes ?? null;
            $cases->person = $person->name;

            return response()->json(compact('cases'));
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
     * @param Request $request
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
