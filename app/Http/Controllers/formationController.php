<?php

namespace App\Http\Controllers;

use App\Models\taxis_prov;
use App\Models\tdan;
use App\Models\tmar;
use App\Models\tper;
use Illuminate\Http\Request;

class formationController extends Controller
{
    public function confirmer_taxis_prov()
    {
        $taxis = taxis_prov::all();
        return view('formation.confirmation', compact(['taxis']));
    }
    public function transport_personne()
    {
        $taxis = tper::all();
        $type_insc = "Tansport personne";
        return view('formation.participants_dynamique', compact(['type_insc', 'taxis']));
    }
    public function transport_marchandise()
    {
        $taxis = tmar::all();
        $type_insc = "Tansport Marchendise";
        return view('formation.participants_dynamique', compact(['type_insc', 'taxis']));
    }
    public function transport_danger()
    {
        $taxis = tdan::all();
        $type_insc = "Tansport Materieux Dangereux";
        return view('formation.participants_dynamique', compact(['type_insc', 'taxis']));
    }
}
