<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:company'], function () {
    Route::resource('monitoringPerson', 'MonitoringPersonController')->only(['show']);
});
