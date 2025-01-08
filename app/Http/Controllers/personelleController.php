<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class personelleController extends Controller
{
    public function personelle_stat(Request $request){
        return view('personelle.statistiques');
    }

}
