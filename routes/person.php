<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:company'], function () {

    Route::get('person/import', 'PersonController@importView')->name('person.import');
    Route::post('person/import', 'PersonController@import')->name('person.import');
    Route::resource('person', 'PersonController');

    Route::post('multi/monitoring', 'Company\CompaniesController@multiMonitoring')->name('multi.monitoring');
});
