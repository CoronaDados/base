<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Imports\CompanyUsersImport;
use App\Imports\PersonsImport;
use App\Model\Company\Company;
use App\Model\Company\CompanyUser;
use App\Model\People\CasePeople;
use App\Model\People\People;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class CompaniesController extends Controller
{
    public function dashboard()
    {
        $peoplesCompany = auth('company')->user()->countPersons();
        $peoplesUser =   auth('company')->user()->persons()->count();

        return view('company.dashboard', compact(['peoplesCompany', 'peoplesUser']));
    }

    public function tips()
    {
        return view('company.tips');
    }

    public function monitoring(Request $request)
    {
        if ($request->ajax()) {
            $datas =  auth('company')->user()->persons()->with('casePeopleDay')->get();
            foreach ($datas as $data) {
                if (!$data->casePeopleDay()->exists()) {
                    $person[] = $data;
                }
            }

            return DataTables::of($person)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-name="' . $row->name . ' <br>Peça para enviar uma mensagem no whats com esse codigo: <strong>' . $row->code . '</strong>" data-status="' . $row->status . '"  data-id="' . $row->id . '" data-original-title="Monitorar" class="edit btn btn-primary btn-sm editMonitoring">Monitorar</a>';
                    //$btn = '<a href="javascript:void(0)" class="editMonitoring btn btn-primary btn-sm">Ver</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('company.monitoring');
    }

    public function storeMonitoring($id, Request $request)
    {
        if (!$person = auth('company')->user()->persons()->where('id', '=', $id)->first()) {
            return response()->json('error', 401);
        };
        $monitoring = new CasePeople(['status' => json_encode($request->all())]);

        $person->createCasePeopleDay()->save($monitoring);

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
     * @return \Illuminate\Http\Response
     */
    public function multiMonitoring(Request $request)
    {

        if (!$persons = auth('company')->user()->persons()->whereIn('id', $request->id)->get()) {
            return response()->json('error', 401);
        };


        foreach ($persons as $person) {
            $monitoring = new CasePeople(['status' => 'ok']);
            $person->createCasePeopleDay()->save($monitoring);
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
