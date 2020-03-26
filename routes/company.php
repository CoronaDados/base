<?php

use Illuminate\Support\Facades\Route;


Auth::routes();

//Route::get('login', 'Auth\LoginController@showCompanyLoginForm')->name('login');
//Route::post('login', '\App\Http\Controllers\Auth\LoginController@companyLogin');


//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth:company'], function () {
    Route::get('', 'CompaniesController@dashboard')->name('home');
    Route::get('people/add', 'CompaniesController@addPeople')->name('add_people');
    Route::post('people/add', 'CompaniesController@storePeople')->name('add_people');
    Route::get('companies/monitoring', 'CompaniesController@monitoring')->name('monitoring');
    Route::post('companies/monitoring/{id}', 'CompaniesController@storeMonitoring');

    Route::get('import', 'CompaniesController@importView');
    Route::post('import', 'CompaniesController@import')->name('import');

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


