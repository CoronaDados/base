<?php

namespace App\Http\Controllers;

use App\Enums\StatusCovidTestType;
use App\Enums\StatusCovidType;
use App\Helpers\Helper;
use App\Http\Requests\StorePerson;
use App\Imports\CompanyUsersImport;
use App\Imports\PersonablesImport;
use App\Enums\RiskGroupType;
use App\Enums\SectorType;
use App\Model\Company\CompanyUser;
use App\Model\Person\Person;
use App\Model\Person\RiskGroupPerson;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Factory|View
     * @throws Exception
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
        $riskGroupsType = RiskGroupType::getValues();
        $sectors = SectorType::getValues();
        $roles = Role::query()->where('guard_name', '=', 'company')->get();
        $leaders = auth('company')->user()->leadersInCompany();

        return view('person.create', compact('riskGroupsType', 'sectors', 'roles', 'leaders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Factory|View
     * @throws ValidationException
     */
    public function store(StorePerson $request)
    {

        $request->validated();

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

        if($request->risk_groups) {
            $riskGroups = array_map(function($riskGroup) {
               return new RiskGroupPerson(['name' => $riskGroup]);
            }, $request->risk_groups);

            $person->riskGroups()->saveMany($riskGroups);
        } else {
            $riskGroup = new RiskGroupPerson([
                'name' => RiskGroupType::NAO
            ]);

            $person->riskGroups()->save($riskGroup);
        }

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
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function edit(Request $request, $id): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(StorePerson $request, $id): ?JsonResponse
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

                $person->riskGroups()->delete();

                if($request->risk_groups) {
                    $riskGroups = array_map(function($riskGroup) {
                        return new RiskGroupPerson(['name' => $riskGroup]);
                    }, $request->risk_groups);

                    $person->riskGroups()->saveMany($riskGroups);
                } else {
                    $riskGroup = new RiskGroupPerson([
                        'name' => RiskGroupType::NAO
                    ]);

                    $person->riskGroups()->save($riskGroup);
                }

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
     * @return Response
     */
    public function destroy($id): ?Response
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

        flash()->overlay('Importação iniciada com sucesso!<br>Aguarde alguns minutos para ver os colaboradores.<br>Lembre-se que apenas Líderes e Admin tem acesso ao sistema e a senha é o CPF sem pontos ou traços', 'Importação de colaboradores');

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

    public function profileUpdate(StorePerson $request)
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
