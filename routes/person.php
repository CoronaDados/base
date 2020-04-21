<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:company'], function () {

    Route::get('person/profile', 'PersonController@profileShow')->name('person.profile');
    Route::post('person/profile', 'PersonController@profileUpdate')->name('person.profile');

    Route::get('person/import', 'PersonController@importView')->name('person.import');
    Route::post('person/import', 'PersonController@import')->name('person.import');

    Route::resource('person', 'PersonController');
});
