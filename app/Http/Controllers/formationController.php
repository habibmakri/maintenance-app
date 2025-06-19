<?php

namespace App\Http\Controllers;

use App\Models\counters_formation;
use App\Models\taxis_prov;
use App\Models\tdan;
use App\Models\tmar;
use App\Models\tper;
use Carbon\Carbon;
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

    function getNextFormattedNumber($typePrefix, $date)
    {
        // $year = $date->year;
        $carbonDate = Carbon::parse($date);
        $year = $carbonDate->year;
        $key = "{$typePrefix}_{$year}";

        $counter = counters_formation::firstOrCreate(['type' => $key], ['last_number' => 0,'date'=>$date]);
        $counter->last_number += 1;
        $counter->save();

        return sprintf('%s/%02d', $year, $counter->last_number);
        // return "{$year}/{$year}";
    }

    public function valider_transport(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required',
            'id_participant' => 'required',
            'type_insc' => 'required',
        ]);
        // dd($request);
        $participant = null;
        if ($request->type_insc == 'Tansport personne') {
            $participant = tper::find($request->id_participant);
        } else if ($request->type_insc == 'Tansport Marchendise') {
            $participant = tmar::find($request->id_participant);
        } else if ($request->type_insc == 'Tansport Materieux Dangereux') {
            $participant = tdan::find($request->id_participant);
        }
        if(!$participant){
            return redirect()->back()->withErrors(['id_participant' => 'Error']);
        }
        $type_insc = str_replace(' ', '_', $request->type_insc);
        $validation_number = $this->getNextFormattedNumber($type_insc, $request->date);
        $payment_number = $this->getNextFormattedNumber('Payment', $request->date);

        $participant->update([
            'payment_number' => $payment_number,
            'validation_number' => $validation_number,
            'date_paiement' => $request->date,
        ]);
        if ($request->type_insc == 'Tansport personne') {
            return redirect()->back()->with('success', $participant->nom_fr.' '.$participant->prenom_fr.' Validé avec succes!');
        } else if ($request->type_insc == 'Tansport Marchendise') {
            return redirect()->back()->with('success', $participant->nom_fr.' '.$participant->prenom_fr.' Validé avec succes!');
        } else if ($request->type_insc == 'Tansport Materieux Dangereux') {
            return redirect()->back()->with('success', $participant->nom_fr.' '.$participant->prenom_fr.' Validé avec succes!');
        }
    }
}
