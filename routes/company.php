<?php

use Illuminate\Support\Facades\Route;


Auth::routes();

//Route::get('login', 'Auth\LoginController@showCompanyLoginForm')->name('login');
//Route::post('login', '\App\Http\Controllers\Auth\LoginController@companyLogin');


//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth:company'], function () {

    Route::resource('roles', 'RoleController');

    Route::get('users/import', 'UserController@viewImport')->name('users.import');
    Route::post('users/import', 'UserController@import')->name('users.import');
    Route::resource('users', 'UserController');

    Route::get('/', 'CompaniesController@dashboard')->name('home');
    Route::get('person/add', 'CompaniesController@addPerson')->name('person.create');
    Route::post('person/add', 'CompaniesController@storePeople')->name('person.store');
    Route::get('person/list', 'CompaniesController@listPerson')->name('person.list');
    Route::post('person/multi/monitoring', 'CompaniesController@multiMonitoring')->name('person.multi.monitoring');

    Route::get('companies/monitoring', 'CompaniesController@monitoring')->name('monitoring');
    Route::post('companies/monitoring/{id}', 'CompaniesController@storeMonitoring');

    Route::get('person/import', 'CompaniesController@importView')->name('person.import');
    Route::post('person/import', 'CompaniesController@import')->name('person.import');

    Route::get('files/{file}', function ($file) {
        return response()->download(storage_path('files/' . $file));
    })->name('files');

    /*
    Route::get('test', function (){

        $datas =  auth('company')->user()->persons()->with('casePeopleDay')->get();
        foreach ($datas as $data){
            if($data->casePeopleDay()->exists()){
                $person[] = $data;
            }
        }
        dd($person);
    });*/
});
