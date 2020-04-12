<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Imports\CompanyUsersImport;
use App\Model\Company\CompanyUser;
use App\Model\Person\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $users = auth()->user()->company()->first()->users()->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-name="' . $row->name . '" data-status="' . $row->status . '"  data-id="' . $row->id . '" data-original-title="Monitorar" class="edit btn btn-primary btn-sm editUser">Ver / Editar</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $roles = Role::query()->where('guard_name', '=', 'company')->pluck('name');

        return view('company.users.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('company.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = CompanyUser::create([
            'company_id' => auth()->user()->company_id,
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => false,
            'password' => Hash::make('secret@'),
        ]);

        $person = new Person();
        $person->name = $request->name;
        $person->email = $request->email;
        $person->cpf = $request->cpf;
        $person->phone = $request->phone;
        $person->sector = $request->sector;
        $person->bithday = $request->bithday;
        $person->gender = $request->gender;
        $person->risk_group = $request->risk_group;
        $person->status = $request->status;
        $person->cep = $request->cep;
        $person->ibge = $request->ibge;
        $person->state = $request->state;
        $person->city = $request->city;
        $person->neighborhood = $request->neighborhood;
        $person->street = $request->street;
        $person->complement = $request->complement;
        $person->more = $request->more;
        //$person->save();
        $user->persons()->save($person);

        flash('Usuário cadastrado com sucesso', 'info');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $roles = Role::query()->where('guard_name', '=', 'company')->pluck('name');
            $user = CompanyUser::query()->where('id', '=', $id)->with('roles')->first();
            return response()->json(['user' => $user, 'roles' => $roles]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $user = CompanyUser::query()->where('id', '=', $id)->first();
            if ($request['password'])
                if ($request['password'] === $request['confirm_password']) {
                    $user->password = Hash::make($request['password']);
                } else {
                    return response()->json(['success' => false, 'error' => true, "message" => 'Senha não confirmada'], 400);
                }
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->cpf = $request['cpf'];
            $user->phone = $request['phone'];
            $user->syncRoles($request['role']);
            $user->save();

            return response()->json(['success' => true, "message" => 'Usuário atualizado com sucesso']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function viewImport()
    {
        $roles = Role::query()->where('guard_name', '=', 'company')->get();
        return view('company.users.import', compact('roles'));
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $role_name = $request->role;

        (new CompanyUsersImport(auth('company')->user(), $role_name))->queue($file);

        flash()->overlay('Importação iniciada com sucesso!<br>Aguarde algums minutos para ver os usuários.<br>Lembre-se que a senha dos usuários é o CPF sem pontos ou traços', 'Importação de usuários');

        return back();
    }
}
