<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\People;


class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('companies.add_people');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'superior' => 'required',
            'setor' => 'required',
            'nome' => 'required',
            'cpf' => 'required',
            'data_nascimento' => 'required',
            'cep' => 'required',
            'bairro' => 'required'
        ]);


        $people = new People();
        $people->nm_people = $request->get('nome');
        $people->nm_superior = $request->get('superior');
        $people->nm_setor = $request->get('setor');
        $people->ds_cpf = $request->get('cpf');
        $people->dt_nascimento = $request->get('data_nascimento');
        $people->save();

        
        // return $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return "ID: ${id}";
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
