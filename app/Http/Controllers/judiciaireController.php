<?php

namespace App\Http\Controllers;

use App\Http\Requests\add_bus_request;
use App\Http\Requests\add_ligne_request;
use App\Http\Requests\add_panne_request;
use App\Http\Requests\add_piece_request;
use App\Http\Requests\add_user_request;
use App\Http\Requests\edit_bus_request;
use App\Http\Requests\edit_ligne_request;
use App\Http\Requests\edit_user_request;
use App\Models\Bus;
use App\Models\chauffeurs;
use App\Models\Ligne;
use App\Models\Panne;
use App\Models\pieces_maintanance;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;

class judiciaireController extends Controller
{
    public function judiciaire_in()
    {
        $buses = Bus::all();
        $chauffeurs = chauffeurs::all();
        $lines = Ligne::all();
        return view("judiciaire.declare", compact(['buses','chauffeurs','lines']));
    }

    
    
}
