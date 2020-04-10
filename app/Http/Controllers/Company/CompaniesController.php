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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */

    public function addPerson(Request $request)
    {
        return view('company.person.create');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function listPerson(Request $request)
    {
        if ($request->ajax()) {
            if (auth('company')->user()->can('Ver Usuários')) {
                $data =  auth('company')->user()->personsInCompany();
            } else {
                $data =  auth('company')->user()->persons()->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"data-original-title="Ver" class="edit btn btn-primary btn-sm editMonitoring">Ver / Editar</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $roles = Role::query()->where('guard_name', '=', 'company')->pluck('name');

        return view('company.person.list', compact('roles'));
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
        flash('Colaborador cadastrado com sucesso', 'info');
        return view('company.person.create');
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
        $roles = Role::query()->where('guard_name', '=', 'company')->get();
        return view('company.import', compact('roles'));
    }

    public function import(Request $request)
    {
        $companyID = auth('company')->user()->company_id;
        $file = $request->file('file');

        (new PersonsImport($companyID))->queue($file);
        flash()->overlay('Importação realizada com sucesso, aguarde algums minutos para ver os colaboradores<br> Lembre-se que a senha dos usuários é o cpf sem pontos ou traços', 'Importação de colaboradores');
        return back();
    }
}
