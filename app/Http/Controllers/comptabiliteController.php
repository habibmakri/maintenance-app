<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class comptabiliteController extends Controller
{
    public function comptabilite_stat(Request $request){
        return view('comptabilite.statistiques');
    }

}
