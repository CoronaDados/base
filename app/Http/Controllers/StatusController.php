<?php

namespace App\Http\Controllers;

class StatusController extends Controller
{
    public function healthCheck()
    {
        return response()->json(['status' => 'ok'], 200); 
    }

}
