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
use App\Models\extincteurs;
use App\Models\gasoile_cartes;
use App\Models\Ligne;
use App\Models\Panne;
use App\Models\pieces_maintanance;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;

class gestionController extends Controller
{
    public function manage_user()
    {
        $users = User::all();
        return view("gestion.manage_user", compact("users"));
    }
    public function add_user()
    {
        return view("gestion.add_user");
    }
    public function do_add_user(add_user_request $request)
    {
        $data = $request->all();
        $data['username'] = str_replace(' ', '_', $data['firstname'] . $data['lastname']);
        $data['autorisations'] = implode(',', $request->autorisations);
        User::create($data);
        return to_route('app.gestion.manage_user')->with('success', 'Compte créé avec succès!');
    }
    public function delete_user($id)
    {
        $record = User::find($id);
        if ($record) {
            $record->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    public function edit_user($id)
    {
        $record = User::find($id);
        if ($record) {
            $record->autorisations = explode(',', $record->autorisations);
            return view('gestion.edit_user', compact('record'));
        }
        abort(404);
    }
    public function do_edit_user(edit_user_request $request, $id)
    {
        $data = $request->all();
        $data['username'] = str_replace(' ', '_', $data['firstname'] . $data['lastname']);
        $data['autorisations'] = implode(',', $request->autorisations);
        
        $ficheitem = $request->validated();
        $record = User::find($id);
        if ($record) {
            $record->update([
                'username' => $data['username'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'telephone' => $data['telephone'],
                'service' => $data['service'],
                'autorisations' => $data['autorisations'],
            ]);
            
            return to_route('app.gestion.manage_user')->with('success', 'Compte modifié avec succès!');
        }else{
            return to_route('app.gestion.manage_user')->with('error', 'Erreur!');
            
        }
    }
    public function manage_bus()
    {
        $buses = Bus::all();
        return view("gestion.manage_bus", compact("buses"));
    }
    public function add_bus()
    {
        return view("gestion.add_bus");
    }
    public function do_add_bus(add_bus_request $request)
    {
        $data = $request->all();
        $data["ligne_id"] = 1;
        $data["type"] = 'bus';
        Bus::create($data);
        return to_route('app.gestion.manage_bus')->with('success', 'Bus créé avec succès!');
    }
    public function edit_bus($id)
    {
        $record = Bus::find($id);
        if ($record) {
            return view('gestion.edit_bus', compact('record'));
        }
        abort(404);
    }
    public function do_edit_bus(edit_bus_request $request, $id)
    {
        $data = $request->all();
        $record = Bus::find($id);
        if ($record) {
            $record->update([
                'kmactuelle' => $data['kmactuelle'],
                'derniervidange' => $data['derniervidange'],
            ]);
            
            return to_route('app.gestion.manage_bus')->with('success', 'Bus modifié avec succès!');
        }else{
            return to_route('app.gestion.manage_bus')->with('error', 'Erreur!');
            
        }
    }
    public function manage_ligne()
    {
        $lignes = Ligne::all();
        return view("gestion.manage_ligne", compact("lignes"));
    }
    public function add_ligne()
    {
        $stations = Station::all();
        return view("gestion.add_ligne" ,compact('stations'));
    }
    public function do_add_ligne(add_ligne_request $request)
    {
        $data = $request->all();
        Ligne::create($data);
        return to_route('app.gestion.manage_ligne')->with('success', 'Ligne créé avec succès!');
    }
    public function edit_ligne($id)
    {
        $record = Ligne::find($id);
        $stations = Station::all();
        if ($record) {
            return view('gestion.edit_ligne', compact('record','stations'));
        }
        abort(404);
    }
    public function do_edit_ligne(edit_ligne_request $request, $id)
    {
        $data = $request->all();
        $record = Ligne::find($id);
        if ($record) {
            $record->update([
                'station_id' => $data['station_id'],
                'terminus' => $data['terminus'],
            ]);
            
            return to_route('app.gestion.manage_ligne')->with('success', 'Ligne modifié avec succès!');
        }else{
            return to_route('app.gestion.manage_ligne')->with('error', 'Erreur!');
            
        }
    }
    public function manage_panne()
    {
        $pannes = Panne::all();
        return view("gestion.manage_panne", compact("pannes"));
    }
    public function add_panne()
    {
        $stations = Station::all();
        return view("gestion.add_panne" ,compact('stations'));
    }
    public function do_add_panne(add_panne_request $request)
    {
        $data = $request->all();
        Panne::create($data);
        return to_route('app.gestion.manage_panne')->with('success', 'Panne créé avec succès!');
    }
    public function delete_panne($id)
    {
        $record = Panne::find($id);
        if ($record) {
            $record->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    public function manage_piece()
    {
        $pieces = pieces_maintanance::all();
        // dd($pieces);
        return view("gestion.manage_piece", compact("pieces"));
    }
    public function add_piece()
    {
        return view("gestion.add_piece");
    }
    public function do_add_piece(add_piece_request $request)
    {
        $data = $request->all();
        pieces_maintanance::create($data);
        return to_route('app.gestion.manage_piece')->with('success', 'piece créé avec succès!');
    }
    public function delete_piece($id)
    {
        $record = pieces_maintanance::find($id);
        if ($record) {
            $record->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    public function manage_extincteurs(){
        $extincteurs = extincteurs::all();
        return view('gestion.extincteurs', compact(['extincteurs']));
    }
    public function add_extincteur(){
        return view('gestion.add_extincteurs');
    }
    public function do_add_extincteur(Request $request)
    {
        extincteurs::create([
            'reference' => $request->reference,
            'type' => $request->type,
            'bus' => $request->bus == 1 ? true : false,
            'affectation' => $request->Affectation,
            'date_recharge' => $request->date_recharge,
            'date_expiration' => $request->date_expiration,
        ]);
        return to_route('app.gestion.manage_extincteurs')->with('success', 'Extincteur créé avec succès!');
    }
    
    public function recharge_extincteur(Request $request)
    {
        $request->validate([
            'extincteurid' => 'required | exists:extincteur,id'
        ]);
        $extincteur = extincteurs::find($request->extincteurid);
        if($extincteur){
            $extincteur->update([
               'date_recharge' => $request->date_recharge,
               'date_expiration' => $request->date_expiration
            ]); 
            return to_route('app.gestion.manage_extincteurs')->with('success', 'Extincteur Rechargé avec succès!');
        }else{
            return to_route('app.gestion.manage_extincteurs')->with('error', 'Extincteur n\'existe pas!');
        }
        
    }

    public function manage_cartes_gasoile(){
        $cartes = gasoile_cartes::all();
        return view('gestion.cartes_gasoiles', compact(['cartes']));
    }
    public function add_carte_gasoile(){
        return view('gestion.add_cartes_gasoile');
    }
    public function do_add_carte_gasoile(Request $request)
    {
        gasoile_cartes::create([
            'number' => $request->reference,
            'name' => $request->name,
            'initial_balance' => $request->solde,
            'actual_balance' => $request->solde,
            'state' => $request->valid,
            'date_expiration' => $request->date_expiration,
        ]);
        return to_route('app.gestion.manage_cartes_gasoile')->with('success', 'Cartes créé avec succès!');
    }
}
