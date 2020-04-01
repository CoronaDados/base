<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Model\Company\Company;
use App\Model\Company\CompanyUser;
use App\Model\People\People;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-name="'.$row->name.' <br>Peça para enviar uma mensagem no whats com esse codigo: <strong>'. $row->code.'</strong>" data-status="'.$row->status.'"  data-id="'.$row->id.'" data-original-title="Monitorar" class="edit btn btn-primary btn-sm editMonitoring">Ver</a>';
                    //$btn = '<a href="javascript:void(0)" class="editMonitoring btn btn-primary btn-sm">Ver</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('company.users.index');
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
     * @param  \Illuminate\Http\Request  $request
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
        $user->persons()->save($people);

        flash('Usuário cadastrado com sucesso', 'info');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
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
