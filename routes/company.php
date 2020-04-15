<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth:company']], function () {

    Route::resource('roles', 'RoleController');

    Route::get('/', 'CompaniesController@dashboard')->name('home');
    Route::get('/tips', 'CompaniesController@tips')->name('tips');
    Route::get('/help', 'CompaniesController@help')->name('help');

    Route::get('companies/monitoring', 'CompaniesController@monitoring')->name('monitoring');
    // Route::post('companies/monitoring', 'CompaniesController@monitoring')->name('monitoring');
    Route::post('companies/monitoring/{id}', 'CompaniesController@storeMonitoring');

    Route::get('companies/monitoring/history', 'CompaniesController@monitoringHistory')->name('monitoring.history');

    Route::get('files/{file}', function ($file) {
        return response()->download(storage_path('files/' . $file));
    })->name('files');
});
