<?php

use Illuminate\Support\Facades\Route;


Auth::routes();

//Route::get('login', 'Auth\LoginController@showCompanyLoginForm')->name('login');
//Route::post('login', '\App\Http\Controllers\Auth\LoginController@companyLogin');


//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth:company'], function () {
    Route::resource('users', 'UserController');
    Route::get('', 'CompaniesController@dashboard')->name('home');
    Route::get('person/add', 'CompaniesController@addPerson')->name('addPerson');
    Route::post('people/add', 'CompaniesController@storePeople')->name('add_people');
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


