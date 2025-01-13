<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ctechniqueController extends Controller
{
    
    public function rate_ctechnique(Request $request){
        return view('ctechnique.rate_us');
    }
}
