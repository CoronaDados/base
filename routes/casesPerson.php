<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:company'], function () {
    Route::resource('casesPerson', 'CasesPersonController')->only(['store', 'show']);
});
