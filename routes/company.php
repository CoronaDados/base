<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::group(['middleware' => 'auth:company'], function () {

    Route::resource('roles', 'RoleController');

    Route::get('/', 'CompaniesController@dashboard')->name('home');
    Route::get('/tips', 'CompaniesController@tips')->name('tips');

    Route::get('users/import', 'UserController@viewImport')->name('users.import');
    Route::post('users/import', 'UserController@import')->name('users.import');
    Route::resource('users', 'UserController');

    Route::get('companies/monitoring', 'CompaniesController@monitoring')->name('monitoring');
    Route::post('companies/monitoring/{id}', 'CompaniesController@storeMonitoring');

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
