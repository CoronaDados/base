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
use Yajra\DataTables\Facades\DataTables;

class CompaniesController extends Controller
{


    public function dashboard()
    {
        $peoplesCompany = auth()->user()->countPersons();
        $peoplesUser =   auth()->user()->persons()->count();

        return view('company.dashboard',compact(['peoplesCompany','peoplesUser']));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */

    public function addPerson(Request $request)
    {
        //$peoples = auth('company')->user()->persons()->get();
        if ($request->ajax()) {
            $data =  auth('company')->user()->persons()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('company.addPerson');
    }

    public function monitoring(Request $request)
    {
        if ($request->ajax()) {
            $datas =  auth('company')->user()->persons()->with('casePeopleDay')->get();
            foreach ($datas as $data){
                if(!$data->casePeopleDay()->exists()){
                    $person[] = $data;
                }
            }

            return DataTables::of($person)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-name="'.$row->name.' <br>Peça para enviar uma mensagem no whats com esse codigo: <strong>'. $row->code.'</strong>" data-status="'.$row->status.'"  data-id="'.$row->id.'" data-original-title="Monitorar" class="edit btn btn-primary btn-sm editMonitoring">Monitorar</a>';
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
        if(!$person = auth('company')->user()->persons()->where('id','=',$id)->first()){
            return response()->json('error',401);
        };
        $monitoring = new CasePeople(['status' => json_encode($request->all())]);

        $person->createCasePeopleDay()->save($monitoring);

        return true;

    }

    public function storePeople(Request $request)
    {

        $people = new People();
        $people->name = $request->name;
        $people->email = $request->email;
        $people->cpf = $request->cpf;
        $people->phone = $request->phone;
        $people->sector = $request->sector;
        $people->bithday = $request->bithday;
        $people->gender = $request->gender;
        $people->risk_group = $request->risk_group;
        $people->status = $request->status;
        $people->cep = $request->cep;
        $people->ibge = $request->ibge;
        $people->state = $request->state;
        $people->city = $request->city;
        $people->neighborhood = $request->neighborhood;
        $people->street = $request->street;
        $people->complement = $request->complement;
        $people->more = $request->more;
        //$people->save();
        auth('company')->user()->persons()->save($people);
        //$peoples = auth('company')->user()->persons()->get();
        return view('company.add_people');
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
     * @param  \App\Model\Company\Company  $companies
     * @return \Illuminate\Http\Response
     */
    public function show(Company $companies)
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

    public function importView()
    {
        if(!auth()->user()->isAdmin){
            return back();
        }
        return view('company.import');
    }

    public function import()
    {
        if(!auth()->user()->isAdmin){
            return back();
        }
        Excel::queueImport(new PersonsImport(),request()->file('file'));

        return back();
    }

    public function importView2()
    {
        if(!auth()->user()->isAdmin){
            return back();
        }
        return view('company.import2');
    }

    public function import2()
    {
        if(!auth()->user()->isAdmin){
            return back();
        }
        Excel::queueImport(new CompanyUsersImport(),request()->file('file'));

        return back();
    }
}
