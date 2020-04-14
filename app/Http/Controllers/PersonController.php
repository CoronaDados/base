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
                if (auth()->user()->hasRole('Admin')) {
                    $data =  auth('company')->user()->personsInCompany();
                } else {
                    $data =  auth('company')->user()->personsInCompanyByLeader();
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-name="' . $row->name . '" data-id="' . $row->id . '" data-original-title="Ver / Editar" class="edit btn btn-primary btn-sm editPerson">Ver / Editar</a>';
                    return $btn;
                })
                ->editColumn('name', function ($user) {
                    return $this->getFirstAndLastName($user->name);
                })
                ->editColumn('lider', function ($user) {
                    return $this->getFirstAndLastName($user->lider);
                })
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
        // $rules = [
        //     'cpf'  => 'required|cpf',
        //     'birthday' => 'required|date|regex:/\d{1,2}\/\d{1,2}\/\d{4}/|before:today'
        // ];

        // $messages = [
        //     'before' => 'O campo :attribute deve ser uma data anterior a hoje.',
        // ];

        // $validator = $this->validate($request, $rules, $messages);

        $companyUser = auth('company')->user();

        $cpf = $this->removePunctuation($request->cpf);

        $person = Person::create(
            [
                'name' => $request->name,
                'cpf' => $cpf,
                'cep' => $this->removePunctuation($request->cep),
                'phone' => $this->removePunctuation($request->phone),
                'sector' => $request->sector,
                'birthday' => Carbon::createFromFormat('d/m/Y', $request->birthday)->format('Y-m-d'),
                'gender' => $request->gender,
                'risk_group' => $request->risk_group,
                'status' => true
            ]
        );

        $user = CompanyUser::create(
            [
                'person_id' => $person->id,
                'company_id' => $companyUser->company_id,
                'email' => $request->email,
                'password' => Hash::make($cpf),
                'email_verified_at' => now(),
                'force_new_password' => true,
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

    private function getFirstAndLastName($name): string
    {
        $explodedName = explode(" ", $name);
        $maxLength = count($explodedName) - 1;
        $firstName = $explodedName[0];
        $lastName = $explodedName[$maxLength];

        return $firstName . ' ' . $lastName;
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
            $companyUser = CompanyUser::with('person', 'roles')->find($id);
            $leader = $companyUser->leader()->id;
            $casesPerson = $companyUser->person->casesPerson()->get();

            $allSymptoms = [
                "febre" => "Febre",
                "tosse-seca" => "Tosse seca",
                "cansaco" => "Cansaço",
                "dor-corpo" => "Dor no corpo",
                "dor-garganta" => "Dor de Garganta",
                "congestao-nasal" => "Congestão Nasal",
                "diarreia" => "Diarreia",
                "dificuldade-respirar" => "Falta de ar/Dificuldade para respirar"
            ];

            foreach ($casesPerson as $case) {
                $object = new \stdClass();

                $status = (array) json_decode($case['status']);
                unset($status["person_id"]);

                $allSymptomsFiltered = array_values(
                    array_filter(
                        $allSymptoms,
                        function ($key) use ($status) {
                            return array_key_exists($key, $status);
                        },
                        ARRAY_FILTER_USE_KEY
                    )
                );

                $object->symptoms = $allSymptomsFiltered;

                $object->leader = CompanyUser::with('person')->find($case['user_id'])->person->name;
                $object->date = Carbon::parse($case['created_at'])->format('d/m/Y H:i:s');
                $object->obs = $status['obs'];

                $cases[] = $object;
            }

            return response()->json(compact('companyUser', 'leader', 'cases'));
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
            $companyUser = CompanyUser::find($id);

            if ($companyUser) {
                $cpf = $this->removePunctuation($request->cpf);

                $person = $companyUser->person;
                $person->name = $request->name;
                $person->cpf = $cpf;
                $person->cep = $this->removePunctuation($request->cep);
                $person->phone = $this->removePunctuation($request->phone);
                $person->birthday = Carbon::createFromFormat('d/m/Y', $request->birthday)->format('Y-m-d');
                $person->gender = $request->gender;
                $person->sector = $request->sector;
                $person->risk_group = $request->risk_group;
                $person->save();

                $companyUser->email = $request->email;

                if ($request->password) {
                    $companyUser->password = Hash::make($request->password);
                }

                $role = $request->role;
                if ($role) {
                    $companyUser->syncRoles($role);
                }

                $leaderId = $request->leader;
                if ($leaderId) {
                    $userLider = CompanyUser::where([
                        'id' => $leaderId,
                        'company_id' => $companyUser->company_id
                    ])->first();
                    $person->companyUsers()->sync($userLider);
                }

                $companyUser->save();
            }

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

    public function profileShow()
    {
        $companyUser = auth('company')->user();
        $riskGroups = RiskGroupType::getValues();
        $sectors = SectorType::getValues();
        $roles = Role::query()->where('guard_name', '=', 'company')->get();
        $leaders = $companyUser->leadersInCompany();
        $leader = $companyUser->leader()->id ?? null;

        return view('person.profile', compact('riskGroups', 'sectors', 'roles', 'leaders', 'companyUser', 'leader'));
    }

    public function profileUpdate(Request $request)
    {
        $companyUser = auth('company')->user();

        $cpf = $this->removePunctuation($request->cpf);

        $person = $companyUser->person;
        $person->name = $request->name;
        $person->cpf = $cpf;
        $person->cep = $this->removePunctuation($request->cep);
        $person->phone = $this->removePunctuation($request->phone);
        $person->birthday = Carbon::createFromFormat('d/m/Y', $request->birthday)->format('Y-m-d');
        $person->gender = $request->gender;
        $person->sector = $request->sector;
        $person->save();

        $companyUser->email = $request->email;

        if ($companyUser->force_new_password && (!$request->password || Hash::check($request->password, $companyUser->password))) {
            flash('A sua nova senha deve ser diferente da anterior!', 'danger');
            return redirect()->route('person.profile');
        }

        if ($request->password) {
            $companyUser->password = Hash::make($request->password);
            $companyUser->force_new_password = false;
        }

        $companyUser->save();

        flash('Dados atualizados com sucesso', 'info');

        return redirect()->route('company.home');
    }
}
