<?php

namespace App\Http\Controllers;

use App\Models\taxis_prov;
use Illuminate\Http\Request;

class formationController extends Controller
{
    public function confirmer_taxis(){
        $taxis = taxis_prov::all();
        return view('formation.confirmation', compact(['taxis']));
        
    }
}
