<?php

use Illuminate\Support\Facades\Route;


Auth::routes();

//Route::get('login', 'Auth\LoginController@showCompanyLoginForm')->name('login');
//Route::post('login', '\App\Http\Controllers\Auth\LoginController@companyLogin');


//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth:company'], function () {
    Route::resource('roles','RoleController');
    Route::resource('users', 'UserController');
    Route::get('', 'CompaniesController@dashboard')->name('home');
    Route::get('person/add', 'CompaniesController@addPerson')->name('person.create');
    Route::post('person/add', 'CompaniesController@storePeople')->name('person.store');
    Route::post('person/multi/monitoring', 'CompaniesController@multiMonitoring')->name('person.multi.monitoring');
    Route::get('companies/monitoring', 'CompaniesController@monitoring')->name('monitoring');
    Route::post('companies/monitoring/{id}', 'CompaniesController@storeMonitoring');

    Route::get('import', 'CompaniesController@importView');
    Route::post('import', 'CompaniesController@import')->name('import');
    Route::get('import2', 'CompaniesController@importView2');
    Route::post('import2', 'CompaniesController@import2')->name('import2');

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


