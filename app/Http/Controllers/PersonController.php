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
use Illuminate\Support\Facades\Hash;
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
//                ->editColumn('name', function ($user) {
//
//                    $name = $user->name;
//                    $explodedName = explode(" ", $name);
//                    $maxLength = count($explodedName);
//                    $firstName = $explodedName[0];
//                    $lastName = $explodedName[$maxLength];
//
//                    return $firstName . ' ' . $lastName;
//                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $riskGroups = RiskGroupType::getValues();
        $sectors = SectorType::getValues();
        $roles = Role::query()->where('guard_name', '=', 'company')->get();
        $leaders = auth('company')->user()->leadersInCompany();

        return view('person.index', compact('riskGroups', 'sectors', 'roles', 'leaders'));
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
        $leaders = auth('company')->user()->leadersInCompany();

        return view('person.create', compact('riskGroups', 'sectors', 'roles', 'leaders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $request)
    {

        $companyUser = auth('company')->user();

        $cpf = $this->removePunctuation($request->cpf);

        $person = Person::create([
            'name' => $request->name,
            'cpf' => $cpf,
            'cep' => $this->removePunctuation($request->cep),
            'phone' => $this->removePunctuation($request->phone),
            'city' => $request->city,
            'sector' => $request->sector,
            'ibge' => $request->ibge,
            'bithday' => Carbon::createFromFormat('d/m/Y', $request->birthday)->format('Y-m-d'),
            'gender' => $request->gender,
            'risk_group' => $request->risk_group,
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = CompanyUser::create(
            [
                'person_id' => $person->id,
                'company_id' => $companyUser->company_id,
                'email' => $request->email,
                'password' => Hash::make($cpf),
            ]
        );

        $role = $request->role ?? 'Colaborador';

        $user->assignRole($role);

        $leaderId = $request->leader;

        if ($leaderId) {
            $userLider = CompanyUser::where([
                'id' => $leaderId,
                'company_id' => $companyUser->company_id
            ])->first();
        }

        if (!isset($userLider) || !$userLider) {
            $userLider = $companyUser;
        }

        $person->companyUsers()->sync($userLider);

        flash('Colaborador cadastrado com sucesso!', 'info');

        $riskGroups = RiskGroupType::getValues();
        $sectors = SectorType::getValues();
        $roles = Role::query()->where('guard_name', '=', 'company')->get();
        $leaders = auth('company')->user()->leadersInCompany();

        return view('person.create', compact('riskGroups', 'sectors', 'roles', 'leaders'));
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

            $cpf = $this->removePunctuation($request->cpf);

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


//        $person = Person::create([
//            'name' => $request->name,
//            'cpf' => $cpf,
//            'cep' => $this->removePunctuation($request->cep),
//            'phone' => $this->removePunctuation($request->phone),
//            'city' => $request->city,
//            'sector' => $request->sector,
//            'ibge' => $request->ibge,
//            'bithday' => Carbon::createFromFormat('d/m/Y', $request->birthday)->format('Y-m-d'),
//            'gender' => $request->gender,
//            'risk_group' => $request->risk_group,
//            'status' => true,
//            'created_at' => now(),
//            'updated_at' => now(),
//        ]);
//
//        $user = CompanyUser::find(
//            [
//                'person_id' => $person->id,
//                'email' => $request->email,
//                'password' => Hash::make($cpf),
//            ]
//        );
//
//        $role = $request->role ?? 'Colaborador';
//
//        $user->assignRole($role);
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
