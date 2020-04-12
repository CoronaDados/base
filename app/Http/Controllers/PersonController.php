<?php

namespace App\Http\Controllers;

use App\Imports\CompanyUsersImport;
use App\Imports\PersonablesImport;
use App\Enums\RiskGroupType;
use App\Enums\SectorType;
use App\Model\Company\CompanyUser;
use App\Model\Person\Person;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
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
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-name="' . $row->name . '" data-id="' . $row->id . '" data-original-title="Ver / Editar" class="edit btn btn-primary btn-sm editPerson">Ver / Editar</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $riskGroups = RiskGroupType::getValues();
        $sectors = SectorType::getValues();
        $roles = Role::query()->where('guard_name', '=', 'company')->get();

        return view('person.index', compact('riskGroups', 'sectors', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $riskGroups = RiskGroupType::getValues();
        $sectors = SectorType::getValues();
        $roles = Role::query()->where('guard_name', '=', 'company')->get();

        return view('person.create', compact('riskGroups', 'sectors', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $request)
    {
        $person = new Person();
        $person->name = $request->name;
        $person->cpf = $this->removePunctuation($request->cpf);
        $person->phone = $this->removePunctuation($request->phone);
        $person->sector = $request->sector;
        $person->bithday = Carbon::createFromFormat('d/m/Y', $request->birthday)->format('Y-m-d');
        $person->gender = $request->gender;
        $person->risk_group = $request->risk_group;
        $person->status = $request->status;
        $person->cep = $this->removePunctuation($request->cep);
        $person->ibge = $request->ibge;
        $person->state = $request->state;
        $person->city = $request->city;
        $person->neighborhood = $request->neighborhood;
        $person->street = $request->street;
        $person->complement = $request->complement;
        $person->more = $request->more;

        $companyUser = new CompanyUser();
        $companyUser->email = $request->email;
//        $companyUser->per

        auth('company')->user()->persons()->save($person);

        flash('Colaborador cadastrado com sucesso', 'info');

        $riskGroups = RiskGroupType::getValues();
        $sectors = SectorType::getValues();
        $roles = Role::query()->where('guard_name', '=', 'company')->get();

        return view('person.index', compact('riskGroups', 'sectors', 'roles'));
    }

    private function removePunctuation($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $person = Person::find($id);
            return response()->json(['person' => $person]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $person = Person::find($id);

            if ($person) {
                $person->fill($request->all());
                $person->cpf = $this->removePunctuation($person->cpf);
                $person->phone = $this->removePunctuation($person->phone);
                $person->cep = $this->removePunctuation($person->cep);
                $person->bithday = Carbon::createFromFormat('d/m/Y', $request->birthday)->format('Y-m-d');
            }

            $person->save();

            flash('Colaborador atualizado com sucesso', 'info');

            return response()->json(['person' => $person]);
        }
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

    public function importView()
    {
        $roles = Role::query()->where('guard_name', '=', 'company')->get();
        return view('person.import', compact('roles'));
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $role_name = $request->role;

        (new CompanyUsersImport(auth('company')->user(), $role_name))->queue($file);
        (new PersonablesImport(auth('company')->user()))->queue($file);

        flash()->overlay('Importação iniciada com sucesso!<br>Aguarde alguns minutos para ver os colaboradores.<br>Lembre-se que a senha dos usuários é o CPF sem pontos ou traços', 'Importação de colaboradores');

        return back();
    }
}
