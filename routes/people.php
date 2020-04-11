<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:company'], function () {

    Route::get('people/import', 'PeopleController@importView')->name('people.import');
    Route::post('people/import', 'PeopleController@import')->name('people.import');
    Route::resource('people', 'PeopleController');

    Route::post('multi/monitoring', 'Company\CompaniesController@multiMonitoring')->name('multi.monitoring');
});
