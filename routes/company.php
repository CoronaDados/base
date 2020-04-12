<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth:company', 'can_login', 'verified']], function () {

    Route::resource('roles', 'RoleController');

    Route::get('/', 'CompaniesController@dashboard')->name('home');
    Route::get('/tips', 'CompaniesController@tips')->name('tips');
    Route::get('/help', 'CompaniesController@help')->name('help');

    Route::get('companies/monitoring', 'CompaniesController@monitoring')->name('monitoring');
    Route::post('companies/monitoring/{id}', 'CompaniesController@storeMonitoring');

    Route::get('files/{file}', function ($file) {
        return response()->download(storage_path('files/' . $file));
    })->name('files');

    /*
    Route::get('test', function (){

        $datas =  auth('company')->user()->persons()->with('casePersonDay')->get();
        foreach ($datas as $data){
            if($data->casePersonDay()->exists()){
                $person[] = $data;
            }
        }
        dd($person);
    });*/
});
