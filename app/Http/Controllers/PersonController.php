<?php

namespace App\Http\Controllers;

use App\Enums\StatusCovidTestType;
use App\Enums\StatusCovidType;
use App\Helpers\Helper;
use App\Imports\CompanyUsersImport;
use App\Imports\PersonablesImport;
use App\Enums\RiskGroupType;
use App\Enums\SectorType;
use App\Model\Company\CompanyUser;
use App\Model\Person\Person;
use App\Model\Person\RiskGroupPerson;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (auth('company')->user()->can('Ver Usuários')) {
                if (auth()->user()->hasRole('Admin')) {
                    $data =  auth('company')->user()->personsInCompany();
                } else {
                    $options = ['byLeader'];
                    $data =  auth('company')->user()->personsInCompany($options);
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" title="Ver / Editar" data-name="' . $row->name . '" data-id="' . $row->id . '"
                                data-original-title="Ver / Editar" class="edit btn btn-primary btn-sm editPerson">Ver / Editar</a>';
                    return $btn;
                })
                ->editColumn('name', function ($user) {
                    return Helper::getFirstAndLastName($user->name);
                })
                ->editColumn('lider', function ($user) {
                    return Helper::getFirstAndLastName($user->lider);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $riskGroupsType = RiskGroupType::getValues();
        $sectors = SectorType::getValues();
        $roles = Role::query()->where('guard_name', '=', 'company')->get();
        $leaders = auth('company')->user()->leadersInCompany();
        $tests = StatusCovidTestType::getValues();
        $status = StatusCovidType::getValues();

        return view('person.index', compact('riskGroupsType', 'sectors', 'roles', 'leaders', 'tests', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
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
     * @return Factory|View
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

        $cpf = Helper::removePunctuation($request->cpf);

        $person = Person::create(
            [
                'name' => $request->name,
                'cpf' => $cpf,
                'cep' => Helper::removePunctuation($request->cep),
                'phone' => Helper::removePunctuation($request->phone),
                'sector' => $request->sector,
                'birthday' => Carbon::createFromFormat('d/m/Y', $request->birthday)->format('Y-m-d'),
                'gender' => $request->gender,
                'status' => true
            ]
        );

        $riskGroups = array_map(function($riskGroup) {
           return new RiskGroupPerson(['name' => $riskGroup]);
        }, $request->risk_groups);

        $person->riskGroups()->saveMany($riskGroups);

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

        $riskGroupsType = RiskGroupType::getValues();
        $sectors = SectorType::getValues();
        $roles = Role::query()->where('guard_name', '=', 'company')->get();
        $leaders = auth('company')->user()->leadersInCompany();

        return view('person.create', compact('riskGroupsType', 'sectors', 'roles', 'leaders'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $companyUser = CompanyUser::with('person', 'person.riskGroups', 'roles')->find($id);
            $leader = $companyUser->leader()->id;
            $countMonitoringsPerson = $companyUser->person->monitoringsPerson()->count();
            $cases = $companyUser->person->casesPerson()->get()->last();

            return response()->json(compact('companyUser', 'leader', 'countMonitoringsPerson', 'cases'));
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
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $companyUser = CompanyUser::find($id);

            if ($companyUser) {
                $cpf = Helper::removePunctuation($request->cpf);

                $person = $companyUser->person;
                $person->name = $request->name;
                $person->cpf = $cpf;
                $person->cep = Helper::removePunctuation($request->cep);
                $person->phone = Helper::removePunctuation($request->phone);

                if ($request->birthday) {
                    $person->birthday = Carbon::createFromFormat('d/m/Y', $request->birthday)->format('Y-m-d');
                }

                $person->gender = $request->gender;
                $person->sector = $request->sector;
                $person->save();

                $riskGroups = array_map(function($riskGroup) {
                    return new RiskGroupPerson(['name' => $riskGroup]);
                }, $request->risk_groups);

                $person->riskGroups()->delete();
                $person->riskGroups()->saveMany($riskGroups);

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

        $cpf = Helper::removePunctuation($request->cpf);

        $person = $companyUser->person;
        $person->name = $request->name;
        $person->cpf = $cpf;
        $person->cep = Helper::removePunctuation($request->cep);
        $person->phone = Helper::removePunctuation($request->phone);
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
