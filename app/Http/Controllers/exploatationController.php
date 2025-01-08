<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class exploatationController extends Controller
{
    public function exploatation_stat(Request $request){
        return view('exploatation.statistiques');
    }

}
