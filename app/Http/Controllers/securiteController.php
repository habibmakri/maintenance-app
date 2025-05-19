<?php

namespace App\Http\Controllers;

use App\Models\extincteurs;



class securiteController extends Controller
{
    public function extincteurs() {
        $extincteurs = extincteurs::all();
        return view('secuirité.extincteurs', compact(['extincteurs']));
    }
}
