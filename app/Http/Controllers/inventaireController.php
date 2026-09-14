<?php

namespace App\Http\Controllers;

use App\Models\emplacements;
use App\Models\extincteurs;
use Illuminate\Http\Request;

class inventaireController extends Controller
{
    public function gestion_inventaire()
    {
        $emplacements = emplacements::all();
        return view('inventaire.gestion',compact('emplacements'));
    }
    public function emplacements_inventaire()
    {
        $emplacements = emplacements::all();
        return view('inventaire.gestion',compact('emplacements'));
    }

}
