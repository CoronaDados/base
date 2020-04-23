<?php

use Illuminate\Support\Facades\Route;

Route::post('chatbot', 'ChatBotController@handle');
