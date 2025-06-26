<?php

namespace App\Http\Controllers;

use App\Models\extincteurs;
use Illuminate\Http\Request;

class securiteController extends Controller
{
    public function extincteurs()
    {
        $extincteurs = extincteurs::all();
        return view('secuirité.extincteurs', compact(['extincteurs']));
    }
    public function recharger_extincteur(Request $request)
    {
        $validated = $request->validate([
            'extincteur_id' => 'required',
            'date_expiration' => 'required',
            'date_rechargement' => 'required',
        ]);
        $extincteur = extincteurs::findOrFail($request->extincteur_id);
        $extincteur->update([
            'date_recharge'=>$request->date_expiration,
            'date_expiration'=>$request->date_rechargement,
        ]);
        return redirect()->back()->with('success',  'extincteur rechargé');
    }
}
