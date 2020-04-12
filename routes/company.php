<?php

use Illuminate\Support\Facades\Route;

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth:company', 'verified']], function () {

    Route::get('users/import', 'UserController@viewImport')->name('users.import');
    Route::post('users/import', 'UserController@import')->name('users.import');
    Route::resource('users', 'UserController');

    Route::resource('roles', 'RoleController');

    Route::get('/', 'CompaniesController@dashboard')->name('home');
    Route::get('/tips', 'CompaniesController@tips')->name('tips');
    Route::get('person/add', 'CompaniesController@addPerson')->name('person.create');
    Route::post('person/add', 'CompaniesController@storePeople')->name('person.store');
    Route::post('person/multi/monitoring', 'CompaniesController@multiMonitoring')->name('person.multi.monitoring');
    Route::get('companies/monitoring', 'CompaniesController@monitoring')->name('monitoring');
    Route::post('companies/monitoring/{id}', 'CompaniesController@storeMonitoring');

    Route::get('import', 'CompaniesController@importView');
    Route::post('import', 'CompaniesController@import')->name('import');
    Route::get('import2', 'CompaniesController@importView2');
    Route::post('import2', 'CompaniesController@import2')->name('import2');

    Route::get('files/{file}', function ($file) {
        return response()->download(storage_path('files/' . $file));
    })->name('files');
});
