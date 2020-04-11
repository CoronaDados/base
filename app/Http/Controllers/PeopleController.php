<?php

namespace App\Http\Controllers;

use App\Imports\PersonsImport;
use App\Model\People\People;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class PeopleController extends Controller
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

        return view('people.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('people.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(Request $request)
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

        return view('people.create');
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
            $person = People::query()->where('id',  $id)->first();
            return response()->json(['person' => $person]);
        }
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

    public function importView()
    {
        $roles = Role::query()->where('guard_name', '=', 'company')->get();
        return view('people.import', compact('roles'));
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
