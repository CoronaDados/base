<?php

use Illuminate\Support\Facades\Route;


Auth::routes();

//Route::get('login', 'Auth\LoginController@showCompanyLoginForm')->name('login');
//Route::post('login', '\App\Http\Controllers\Auth\LoginController@companyLogin');


//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth:company'], function () {
    Route::view('', 'dashboard')->name('add_people');
    Route::view('people/add', 'companies.add_people')->name('add_people');
    Route::view('companies/monitoring', 'companies.monitoring')->name('companies.monitoring');
});


