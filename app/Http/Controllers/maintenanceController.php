<?php

namespace App\Http\Controllers;

use App\Http\Requests\maintenanceEditRequest;
use App\Http\Requests\maintenanceinRequest;
use App\Http\Requests\resoudre_panne_request;
use App\Models\Bus;
use App\Models\chauffeurs;
use App\Models\fichemaintenance;
use App\Models\fichepanne_model;
use App\Models\jaugesmodel;
use App\Models\Ligne;
use App\Models\maintenance_agent;
use App\Models\nd_fichepanne_model;
use App\Models\Panne;
use App\Models\pieces_maintanance;
use App\Models\Station;
use App\Models\traveauxlibre_model;
use App\Models\traveauxlibreusedpieces;
use App\Models\used_pieces;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class maintenanceController extends Controller
{
    public function maintenance_in(Request $request)
    {
        $buses = Bus::whereIn('type', ['v8', 'l5'])->get();
        $lines = Ligne::all();
        $stations = Station::all();
        $pannes = Panne::all();
        $chauffeurs = chauffeurs::orderBy('fr_name')->get();
        // dd($_COOKIE['date']);
        if (isset($_COOKIE['date'])) {
            $date = $_COOKIE['date'];
        } else {
            $date = date('Y-m-d');
        }
        return view("maintenance.maintenancein", compact('buses', 'lines', 'stations', 'pannes', 'chauffeurs', 'date'));
    }

    // public function insertFichemaintenance(maintenanceinRequest $request)
    // {
    //     $ficheitem = $request->validated();
    //     $exists = fichemaintenance::where('id_bus', $ficheitem['bus'])
    //         ->where('date_fiche', $ficheitem['date'])
    //         ->where('brigade', $ficheitem['brigade'])
    //         ->exists();

    //     if ($exists) {
    //         return redirect()->back()->with('error', 'Fiche déjà remplie pour ce bus à cette date.');
    //     }
    //     if ($ficheitem['partit'] == "oui") {
    //         $bus = Bus::find($ficheitem['bus']);
    //         if((float)$bus->kmactuelle <(float)$ficheitem['kmarive'] ){
    //             $bus->update([
    //                 'kmactuelle'=>$ficheitem['kmarive']
    //             ]);
    //         }
    //         fichemaintenance::create([
    //             'user_id' => Auth::user()->id,
    //             'date_fiche' => $ficheitem['date'],
    //             'id_bus' => $ficheitem['bus'],
    //             'id_ligne' => $ficheitem['ligne'],
    //             'brigade' => $ficheitem['brigade'],
    //             'heur_depart' => $ficheitem['hdepart'],
    //             'heur_arrive' => $ficheitem['harrive'],
    //             'gasoile' => $ficheitem['gasoile'],
    //             'kmdepart' => $ficheitem['kmdepart'],
    //             'kmarrive' => $ficheitem['kmarive'],
    //             'kmhlp' => $ficheitem['kmhlp'],
    //             'kmgobale' => $ficheitem['kmarive'] - $ficheitem['kmdepart'],
    //             'kmcommerciale' => ($ficheitem['kmarive'] - $ficheitem['kmdepart']) - $ficheitem['kmhlp'],
    //         ]);
    //     } else {
    //         fichemaintenance::create([
    //             'user_id' => Auth::user()->id,
    //             'date_fiche' => $ficheitem['date'],
    //             'id_bus' => $ficheitem['bus'],
    //             'id_ligne' => null,
    //             'brigade' => $ficheitem['brigade'],
    //             'heur_depart' => "00:00",
    //             'heur_arrive' => "00:00",
    //             'gasoile' => "0",
    //             'kmdepart' => "0",
    //             'kmarrive' => "0",
    //             'kmhlp' => "0",
    //             'kmgobale' => "0",
    //             'kmcommerciale' => "0",
    //         ]);
    //     }
    //     return redirect()->back()->with('success', 'Fiche remplie avec succès.');
    // }
    public function insertFichemaintenance(maintenanceinRequest $request)
    {
        $ficheitem = $request->validated();
        $exists = fichemaintenance::where('id_bus', $ficheitem['bus'])
            ->where('date_fiche', $ficheitem['date'])
            ->where('brigade', $ficheitem['brigade'])
            ->where('declaré', true)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Fiche déjà remplie pour ce bus à cette date.');
        }
        setcookie('date', $ficheitem['date'], 0, '/');
        $ficheData = [
            'user_id' => Auth::user()->id,
            'date_fiche' => $ficheitem['date'],
            'declaré' => true,
            'id_bus' => $ficheitem['bus'],
            'brigade' => $ficheitem['brigade'],
            'id_chauffeur' => $ficheitem['partit'] === 'oui' ? $ficheitem['id_chauffeur'] : null,
            'heur_depart' => $ficheitem['partit'] === 'oui' ? $ficheitem['hdepart'] : '00:00',
            'heur_arrive' => $ficheitem['partit'] === 'oui' ? $ficheitem['harrive'] : '00:00',
            'gasoile' => $ficheitem['gasoile'], //$ficheitem['partit'] === 'oui' ? $ficheitem['gasoile'] : '0',
            'kmdepart' => $ficheitem['partit'] === 'oui' ? $ficheitem['kmdepart'] : '0',
            'kmarrive' => $ficheitem['partit'] === 'oui' ? $ficheitem['kmarive'] : '0',
            'kmhlp' => $ficheitem['partit'] === 'oui' ? $ficheitem['kmhlp'] : '0',
            'kmgobale' => $ficheitem['partit'] === 'oui' ? $ficheitem['kmarive'] - $ficheitem['kmdepart'] : '0',
            'kmcommerciale' => $ficheitem['partit'] === 'oui' ? ($ficheitem['kmarive'] - $ficheitem['kmdepart']) - $ficheitem['kmhlp'] : '0',
            'id_ligne' => $ficheitem['partit'] === 'oui' ? $ficheitem['ligne'] : null,
        ];

        $fiche = fichemaintenance::create($ficheData);

        if ($ficheitem['partit'] === 'oui') {
            $bus = Bus::find($ficheitem['bus']);
            if ((float) $bus->kmactuelle < (float) $ficheitem['kmarive']) {
                $bus->update([
                    'kmactuelle' => $ficheitem['kmarive']
                ]);
            }
        }
        $panneTypes = ['pannemecanique', 'panneelectrique', 'pannetolle'];
        foreach ($panneTypes as $panneType) {
            if (!empty($ficheitem[$panneType])) {
                foreach ($ficheitem[$panneType] as $panneId) {
                    fichepanne_model::create([
                        'fichemaintenance_id' => $fiche->id,
                        'pannnename_id' => $panneId,
                        'solved' => false,
                    ]);
                }
            }
        }
        return redirect()->back()->with('success', 'Fiche remplie avec succès.');
    }
    public function checkBuses(Request $request)
    {
        $date = $request->query('date');
        $brigade = $request->query('brigade');

        $buses = Bus::with('maintenanceRecords')
            ->whereIn('type', ['v8', 'l5'])
            ->get()
            ->map(function ($bus) use ($date, $brigade) {
                $isFilled = $bus->maintenanceRecords
                    ->where('date_fiche', $date)
                    ->where('brigade', $brigade)
                    ->where('declaré', true)
                    ->isNotEmpty();

                return [
                    'id' => $bus->id,
                    'name' => $bus->name,
                    'type' => $bus->type,
                    'kmactuelle' => $bus->kmactuelle,
                    'kmderniervidange' => $bus->derniervidange,
                    'kmderniervidangeboite' => $bus->derniervidangeboite,
                    'kmderniervidangepond' => $bus->derniervidangepond,
                    'filled' => $isFilled,
                ];
            });
        return response()->json($buses);
    }

    public function refreshfichtable(Request $request)
    {
        try {
            // 
            $query = fichemaintenance::query();


            if ($request->datedu) {
                $query->where('date_fiche', '>=', $request->datedu);
            }

            if ($request->dateau) {
                $query->where('date_fiche', '<=', $request->dateau);
            }

            if ($request->brigade) {
                if ($request->brigade == 'jour') {
                    $query->whereIn('brigade', ['soir', 'matin']);
                } else {
                    $query->where('brigade', $request->brigade);
                }
            }
            $query->where('declaré', true);
            $query->with(['bus', 'ligne'])->orderBy('date_fiche')->orderBy('id_bus');

            $data = $query->get()->map(function ($item) {
                if ($item->ligne) {
                    return [
                        'bus' => $item->bus->name,
                        'ligne' => $item->ligne->name,
                        'chauffeur' => $item->chauffeur->fr_name,
                        'heur_depart' => $item->heur_depart,
                        'heur_arrive' => $item->heur_arrive,
                        'gasoile' => $item->gasoile,
                        'kmgobale' => $item->kmgobale,
                        'kmcommerciale' => $item->kmcommerciale,
                        'brigade' => $item->brigade,
                        'date_fiche' => \Carbon\Carbon::parse($item->date_fiche)->format('d/m/Y'),
                    ];
                } else {
                    return [
                        'bus' => $item->bus->name,
                        'ligne' => '/',
                        'chauffeur' => '/',
                        'heur_depart' => $item->heur_depart,
                        'heur_arrive' => $item->heur_arrive,
                        'gasoile' => $item->gasoile,
                        'kmgobale' => $item->kmgobale,
                        'kmcommerciale' => $item->kmcommerciale,
                        'brigade' => $item->brigade,
                        'date_fiche' => \Carbon\Carbon::parse($item->date_fiche)->format('d/m/Y'),
                    ];
                }
            });


            return response()->json(['data' => $data]);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
    public function refreshfixtable(Request $request)
    {
        try {
            // 
            $query = fichemaintenance::query();


            if ($request->date) {
                $query->where('date_fiche', '=', $request->date);
            }
            $query->where('declaré', '=', true);

            $query->with(['bus', 'ligne'])->orderBy('id_bus');

            $data = $query->get()->map(function ($item) {
                if ($item->ligne) {
                    return [
                        'id' => $item->id,
                        'bus' => $item->bus->name,
                        'ligne' => $item->ligne->name,
                        'heur_depart' => $item->heur_depart,
                        'heur_arrive' => $item->heur_arrive,
                        'gasoile' => $item->gasoile,
                        'kmgobale' => $item->kmgobale,
                        'kmcommerciale' => $item->kmcommerciale,
                        'brigade' => $item->brigade,
                        'date_fiche' => \Carbon\Carbon::parse($item->date_fiche)->format('d/m/Y'),
                    ];
                } else {
                    return [
                        'id' => $item->id,
                        'bus' => $item->bus->name,
                        'ligne' => '/',
                        'heur_depart' => $item->heur_depart,
                        'heur_arrive' => $item->heur_arrive,
                        'gasoile' => $item->gasoile,
                        'kmgobale' => $item->kmgobale,
                        'kmcommerciale' => $item->kmcommerciale,
                        'brigade' => $item->brigade,
                        'date_fiche' => \Carbon\Carbon::parse($item->date_fiche)->format('d/m/Y'),
                    ];
                }
            });


            return response()->json(['data' => $data]);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function maintnenance_show(Request $request)
    {
        // $f = fichemaintenance::all();
        // dd($f[12]->chauffeur->fr_name);
        $buses = Bus::all();
        $pieces = pieces_maintanance::all();
        return view('maintenance.maintenanceshow', compact(['buses', 'pieces']));
    }
    public function maintnenance_fix(Request $request)
    {
        return view('maintenance.maintenancefix');
    }
    public function deletefiche($id)
    {
        $record = fichemaintenance::find($id);
        if ($record) {
            $record->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    public function deletefichepanne($id)
    {
        $record = fichepanne_model::find($id);
        if ($record) {
            $record->used_pieces()->delete();
            $record->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    public function deletetraveauxlibre($id)
    {
        $record = traveauxlibre_model::find($id);
        if ($record) {
            $record->used_pieces()->delete();
            $record->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    public function editfiche($id)
    {
        $record = fichemaintenance::find($id);
        $buses = Bus::whereIn('type', ['v8', 'l5'])->get();
        $lines = Ligne::all();
        $stations = Station::all();
        $chauffeurs = chauffeurs::orderBy('fr_name')->get();
        if ($record) {
            return view('maintenance.maintenanceedit', compact('record', 'buses', 'lines', 'stations', 'chauffeurs'));
        }
        return redirect()->back()->withErrors(['Record not found.']);
    }
    public function doeditfiche(maintenanceEditRequest $request, $id)
    {
        $ficheitem = $request->validated();
        $record = fichemaintenance::find($id);
        // dd(route('app.maintenance.maintenance_fix'));

        if (!$record) {
            return redirect()->route('app.maintenance.maintenance_fix')->with('error', 'erreur fiche n\'existe pas');
        } else {
            if ($ficheitem['partit'] == "oui") {
                $record->update([
                    'user_id' => Auth::user()->id,
                    'date_fiche' => $record->date_fiche,
                    'id_bus' => $record->id_bus,
                    'id_ligne' => $ficheitem['ligne'],
                    'id_chauffeur' => $ficheitem['id_chauffeur'],
                    'brigade' => $record->brigade,
                    'heur_depart' => $ficheitem['hdepart'],
                    'heur_arrive' => $ficheitem['harrive'],
                    'gasoile' => $ficheitem['gasoile'],
                    'kmdepart' => $ficheitem['kmdepart'],
                    'kmarrive' => $ficheitem['kmarive'],
                    'kmhlp' => $ficheitem['kmhlp'],
                    'kmgobale' => $ficheitem['kmarive'] - $ficheitem['kmdepart'],
                    'kmcommerciale' => ($ficheitem['kmarive'] - $ficheitem['kmdepart']) - $ficheitem['kmhlp'],
                ]);
            } else {
                $record->update([
                    'user_id' => Auth::user()->id,
                    'date_fiche' => $record->date_fiche,
                    'id_bus' => $record->id_bus,
                    'id_ligne' => null,
                    'id_chauffeur' => null,
                    'brigade' => $record->brigade,
                    'heur_depart' => "00:00",
                    'heur_arrive' => "00:00",
                    'gasoile' => "0",
                    'kmdepart' => "0",
                    'kmarrive' => "0",
                    'kmhlp' => "0",
                    'kmgobale' => "0",
                    'kmcommerciale' => "0",
                ]);
            }
            return redirect()->route('app.maintenance.maintenance_fix')->with(
                'success',
                sprintf(
                    'Modification du Bus %s du %s du %s reussit!',
                    $record->bus->name,
                    $record->brigade,
                    \Carbon\Carbon::parse($record->date_fiche)->format('d/m/Y')
                )
            );
        }
    }

    public function traveaux_libre()
    {
        $traveaux = traveauxlibre_model::query()->whereBetween('date_resoudre', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->endOfMonth()])->get();
        $agents = maintenance_agent::select('id', 'firstname', 'lastname')->get();
        $stations = Station::select('id', 'name')->get();
        $pieces = pieces_maintanance::select('id', 'name')->get();
        $buses = Bus::select('id', 'name')->get();
        $pannenames = Panne::select('id', 'name')->get();
        $typevidanges = Panne::where('type', '=', 'vidange')->get();
        return view('maintenance.traveauxlibre', compact(['traveaux', 'stations', 'buses', 'agents', 'pieces', 'typevidanges']));
    }
    public function ajouter_traveaux_libre(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date'],
            'bus' => ['required', 'exists:buses,id'],
            'travaille' => 'required',
            'brigade' => ['required'],
            'duree' => ['required'],
            'lieuresolu' => 'required',
            'typetravaille' => ['required'],
            'equipe' => 'required|array',
            'grantraveaux' => 'required',
            // 'description' => 'required',
        ]);
        if ($request->grantraveaux == 'oui') {
            $grantraveaux = true;
        } else {
            $grantraveaux = false;
        }
        // dd($request->grantraveaux,$grantraveaux);
        $pieces = $request->input('pieces', []);
        $quantities = $request->input('piece_quantities', []);
        $mergedPieces = [];
        foreach ($pieces as $index => $pieceId) {
            if (isset($quantities[$index])) {
                $mergedPieces[$pieceId] = $quantities[$index];
            }
        }
        $travaille_data = [
            'user_id' => Auth::user()->id,
            'name' => $request->travaille,
            'id_bus' => $request->bus,
            'nature' => $request->typetravaille,
            'date_resoudre' => $request->date,
            'lieu_resoudre' => $request->lieuresolu,
            'brigade' => $request->brigade,
            'equipe' => $request->equipe ? json_encode($request->equipe) : null,
            'delai' => intval($request->duree),
            'grantraveaux' => $grantraveaux,
            'description' => $request->description,
        ];
        $travaille_item = traveauxlibre_model::create($travaille_data);
        if ($mergedPieces) {
            foreach ($mergedPieces as $pieceId => $quantity) {
                traveauxlibreusedpieces::create(
                    [
                        'traveauxlibre_id' => $travaille_item->id,
                        'piece_id' => $pieceId,
                        'quantité' => $quantity,
                    ]
                );
            }
        }
        return redirect()->back()->with('success', 'Travaille ajouter avec succès.');
    }
    public function maintenance_vidange()
    {
        $vidanges = fichepanne_model::query()
            ->join('fiches_maintenance', 'fichepanne.fichemaintenance_id', '=', 'fiches_maintenance.id')
            ->join('pannenames', 'fichepanne.pannnename_id', '=', 'pannenames.id')
            ->where('pannenames.type', '=', "vidange")
            ->orderBy('fiches_maintenance.date_fiche')
            ->get();
        // dd($vidanges[0]);
        $buses = Bus::all();
        $agents = maintenance_agent::all();
        $pieces = pieces_maintanance::whereIn('name', ['Huile 15w40', 'Filtre Gasoile WK723', 'Filtre Gasoile GS150', 'Filtre à huile', 'Filtre à huile Hydrolique', 'Huile G3', 'Huile W10', 'Huile W90', 'Filtre à air'])->get();
        $typevidanges = Panne::where('type', '=', 'vidange')->get();
        return view('maintenance.vidange', compact(['vidanges', 'buses', 'agents', 'pieces', 'typevidanges']));
    }

    public function ajouter_vidange(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date'],
            'bus' => ['required', 'exists:buses,id'],
            'brigade' => ['required'],
            'nomvidange' => ['required'],
            'equipe' => 'nullable|array',
            'kilometrage' => 'required',
        ]);
        $pieces = $request->input('pieces', []);
        $quantities = $request->input('piece_quantities', []);
        $mergedPieces = [];
        foreach ($pieces as $index => $pieceId) {
            if (isset($quantities[$index])) {
                $mergedPieces[$pieceId] = $quantities[$index];
            }
        }
        // dd($request->date);
        $ficheData = [
            'user_id' => Auth::user()->id,
            'date_fiche' => $request['date'],
            'declaré' => false,
            'id_bus' => $request['bus'],
            'id_ligne' => null,
            'brigade' => $request->brigade,
            'id_chauffeur' => null,
            'heur_depart' => "00:00",
            'heur_arrive' => "00:00",
            'gasoile' => "0",
            'kmdepart' => "0",
            'kmarrive' => "0",
            'kmhlp' => "0",
            'kmgobale' => "0",
            'kmcommerciale' => "0",
        ];

        $fiche = fichemaintenance::create($ficheData);
        $fichepanne_data = [
            'fichemaintenance_id' => $fiche->id,
            'pannnename_id' => $request->nomvidange,
            'solved' => true,
            'date_resoudre' => $request->date,
            'lieu_resoudre' => 'Depot',
            'brigade' => $request->brigade,
            'equipe' => $request->equipe ? json_encode($request->equipe) : null,
            'description' => "(Kilométrage:" . $request->kilometrage . ") " . $request->description,
        ];
        $fichepanne = fichepanne_model::create($fichepanne_data);
        if ($mergedPieces) {
            foreach ($mergedPieces as $pieceId => $quantity) {
                used_pieces::create(
                    [
                        'fichepanne_id' => $fichepanne->id,
                        'piece_id' => $pieceId,
                        'quantité' => $quantity,
                    ]
                );
            }
        }
        $bus = Bus::find($request->bus);
        $typevidange = Panne::find($request->nomvidange);
        if ($typevidange->name == 'Vidange moteur') {
            $bus->update(['derniervidange' => $request->kilometrage, 'vidange_moteur_date' => $request->date]);
        } elseif ($typevidange->name == 'Vidange boite vitesse') {
            $bus->update(['derniervidangeboite' => $request->kilometrage, 'vidange_boite_date' => $request->date]);
        } elseif ($typevidange->name == 'Vidange pond') {
            $bus->update(['derniervidangepond' => $request->kilometrage, 'vidange_pond_date' => $request->date]);
        }
        return redirect()->back()->with('success', 'Vidange ajouter avec succès.');
    }
    public function maintenance_jauge()
    {
        $jauges = fichepanne_model::query()
            ->join('fiches_maintenance', 'fichepanne.fichemaintenance_id', '=', 'fiches_maintenance.id')
            ->join('pannenames', 'fichepanne.pannnename_id', '=', 'pannenames.id')
            ->where('pannenames.type', '=', "jauge")
            ->orderBy('fiches_maintenance.date_fiche')
            ->get();
        // dd($jauges[0]);
        $buses = Bus::whereIn('type', ['v8', 'l5'])->get();
        $agents = maintenance_agent::all();
        $pieces = pieces_maintanance::all();
        $typejauges = Panne::where('type', '=', 'jauge')
            ->whereNotIn('name', ['Jauge huile moteur', 'GLACIOL'])
            ->get();
        $stations = Station::all();
        return view('maintenance.jauge', compact(['jauges', 'buses', 'agents', 'pieces', 'typejauges', 'stations']));
    }
    public function check_jauge_date(Request $request)
    {
        $date = $request->date;
        if ($request->type == 'huilemoteur') {
            $jauge = jaugesmodel::where('date', '=', $date)->where('type_id', '=', 157)->exists();
        } elseif ($request->type == 'glaciole') {
            $jauge = jaugesmodel::where('date', '=', $date)->where('type_id', '=', 161)->exists();
        } elseif ($request->type == 'direction') {
            $jauge = jaugesmodel::where('date', '=', $date)->where('type_id', '=', 158)->exists();
        } elseif ($request->type == 'btv') {
            $jauge = jaugesmodel::where('date', '=', $date)->where('type_id', '=', 159)->exists();
        }
        return response()->json(['exists' => $jauge]);
    }
    public function ajouter_jauge_huilemoteur(Request $request)
    {
        $request->validate([
            'date' => [
                'required',
                'date',
                Rule::unique('jaugesdates', 'date')->where(function ($query) {
                    return $query->where('type_id', 157);
                }),
            ],
            'equipe' => ['required', 'array'],
            'lieu' => ['required'],
            'brigade' => ['required'],
        ]);

        $inputs = $request->except('_token', 'date', 'equipe', 'brigade', 'lieu');
        jaugesmodel::create([
            'date' => $request->date,
            'type_id' => 157,
            'equipe' => $request->equipe ? json_encode($request->equipe) : null,
        ]);
        $i = 0;
        foreach ($inputs as $key => $value) {
            if ($value > 0) {
                echo ($key . "=>" . $value);
                $ficheData = [
                    'user_id' => Auth::user()->id,
                    'date_fiche' => $request['date'],
                    'declaré' => false,
                    'id_bus' => $key,
                    'id_ligne' => null,
                    'brigade' => $request->brigade,
                    'id_chauffeur' => null,
                    'heur_depart' => "00:00",
                    'heur_arrive' => "00:00",
                    'gasoile' => "0",
                    'kmdepart' => "0",
                    'kmarrive' => "0",
                    'kmhlp' => "0",
                    'kmgobale' => "0",
                    'kmcommerciale' => "0",
                ];
                $fiche = fichemaintenance::create($ficheData);
                $fichepanne_data = [
                    'fichemaintenance_id' => $fiche->id,
                    'pannnename_id' => 157,
                    'solved' => true,
                    'date_resoudre' => $request->date,
                    'lieu_resoudre' => $request->lieu,
                    'brigade' => $request->brigade,
                    'equipe' => $request->equipe ? json_encode($request->equipe) : null,
                    'description' => '',
                ];
                $fichepanne = fichepanne_model::create($fichepanne_data);
                used_pieces::create(
                    [
                        'fichepanne_id' => $fichepanne->id,
                        'piece_id' => 2,
                        'quantité' => $value,
                    ]
                );
                $i++;
            }
        }
        return redirect()->back()->with('success', $i . ' Jauges ajouter avec succès.');
    }
    public function ajouter_jauge_glaciole(Request $request)
    {
        $request->validate([
            'date' => [
                'date',
                Rule::unique('jaugesdates', 'date')->where(function ($query) {
                    return $query->where('type_id', 161);
                }),
            ],
            'equipe' => ['required', 'array'],
            'lieu' => ['required'],
            'brigade' => ['required'],
        ]);

        $inputs = $request->except('_token', 'date', 'equipe', 'brigade', 'lieu');
        jaugesmodel::create([
            'date' => $request->date,
            'type_id' => 161,
            'equipe' => $request->equipe ? json_encode($request->equipe) : null,
        ]);
        $i = 0;
        foreach ($inputs as $key => $value) {
            if ($value > 0) {
                echo ($key . "=>" . $value);
                $ficheData = [
                    'user_id' => Auth::user()->id,
                    'date_fiche' => $request['date'],
                    'declaré' => false,
                    'id_bus' => $key,
                    'id_ligne' => null,
                    'brigade' => $request->brigade,
                    'id_chauffeur' => null,
                    'heur_depart' => "00:00",
                    'heur_arrive' => "00:00",
                    'gasoile' => "0",
                    'kmdepart' => "0",
                    'kmarrive' => "0",
                    'kmhlp' => "0",
                    'kmgobale' => "0",
                    'kmcommerciale' => "0",
                ];
                $fiche = fichemaintenance::create($ficheData);
                $fichepanne_data = [
                    'fichemaintenance_id' => $fiche->id,
                    'pannnename_id' => 161,
                    'solved' => true,
                    'date_resoudre' => $request->date,
                    'lieu_resoudre' => $request->lieu,
                    'brigade' => $request->brigade,
                    'equipe' => $request->equipe ? json_encode($request->equipe) : null,
                    'description' => '',
                ];
                $fichepanne = fichepanne_model::create($fichepanne_data);
                used_pieces::create(
                    [
                        'fichepanne_id' => $fichepanne->id,
                        'piece_id' => 9,
                        'quantité' => $value,
                    ]
                );
                $i++;
            }
        }
        return redirect()->back()->with('success', $i . ' Jauges ajouter avec succès.');
    }
    public function ajouter_jauge_direction(Request $request)
    {
        $request->validate([
            'date' => [
                'date',
                Rule::unique('jaugesdates', 'date')->where(function ($query) {
                    return $query->where('type_id', 158);
                }),
            ],
            'equipe' => ['required', 'array'],
            'lieu' => ['required'],
            'brigade' => ['required'],
        ]);

        $inputs = $request->except('_token', 'date', 'equipe', 'brigade', 'lieu');
        jaugesmodel::create([
            'date' => $request->date,
            'type_id' => 158,
            'equipe' => $request->equipe ? json_encode($request->equipe) : null,
        ]);
        $i = 0;
        foreach ($inputs as $key => $value) {
            if ($value > 0) {
                echo ($key . "=>" . $value);
                $ficheData = [
                    'user_id' => Auth::user()->id,
                    'date_fiche' => $request['date'],
                    'declaré' => false,
                    'id_bus' => $key,
                    'id_ligne' => null,
                    'brigade' => $request->brigade,
                    'id_chauffeur' => null,
                    'heur_depart' => "00:00",
                    'heur_arrive' => "00:00",
                    'gasoile' => "0",
                    'kmdepart' => "0",
                    'kmarrive' => "0",
                    'kmhlp' => "0",
                    'kmgobale' => "0",
                    'kmcommerciale' => "0",
                ];
                $fiche = fichemaintenance::create($ficheData);
                $fichepanne_data = [
                    'fichemaintenance_id' => $fiche->id,
                    'pannnename_id' => 158,
                    'solved' => true,
                    'date_resoudre' => $request->date,
                    'lieu_resoudre' => $request->lieu,
                    'brigade' => $request->brigade,
                    'equipe' => $request->equipe ? json_encode($request->equipe) : null,
                    'description' => '',
                ];
                $fichepanne = fichepanne_model::create($fichepanne_data);
                used_pieces::create(
                    [
                        'fichepanne_id' => $fichepanne->id,
                        'piece_id' => 6,
                        'quantité' => $value,
                    ]
                );
                $i++;
            }
        }
        return redirect()->back()->with('success', $i . ' Jauges ajouter avec succès.');
    }
    public function ajouter_jauge_btv(Request $request)
    {
        $request->validate([
            'date' => [
                'date',
                Rule::unique('jaugesdates', 'date')->where(function ($query) {
                    return $query->where('type_id', 159);
                }),
            ],
            'equipe' => ['required', 'array'],
            'lieu' => ['required'],
            'brigade' => ['required'],
        ]);

        $inputs = $request->except('_token', 'date', 'equipe', 'brigade', 'lieu');
        jaugesmodel::create([
            'date' => $request->date,
            'type_id' => 159,
            'equipe' => $request->equipe ? json_encode($request->equipe) : null,
        ]);
        $i = 0;
        foreach ($inputs as $key => $value) {
            if ($value > 0) {
                echo ($key . "=>" . $value);
                $ficheData = [
                    'user_id' => Auth::user()->id,
                    'date_fiche' => $request['date'],
                    'declaré' => false,
                    'id_bus' => $key,
                    'id_ligne' => null,
                    'brigade' => $request->brigade,
                    'id_chauffeur' => null,
                    'heur_depart' => "00:00",
                    'heur_arrive' => "00:00",
                    'gasoile' => "0",
                    'kmdepart' => "0",
                    'kmarrive' => "0",
                    'kmhlp' => "0",
                    'kmgobale' => "0",
                    'kmcommerciale' => "0",
                ];
                $fiche = fichemaintenance::create($ficheData);
                $fichepanne_data = [
                    'fichemaintenance_id' => $fiche->id,
                    'pannnename_id' => 159,
                    'solved' => true,
                    'date_resoudre' => $request->date,
                    'lieu_resoudre' => $request->lieu,
                    'brigade' => $request->brigade,
                    'equipe' => $request->equipe ? json_encode($request->equipe) : null,
                    'description' => '',
                ];
                $fichepanne = fichepanne_model::create($fichepanne_data);
                used_pieces::create(
                    [
                        'fichepanne_id' => $fichepanne->id,
                        'piece_id' => 8,
                        'quantité' => $value,
                    ]
                );
                $i++;
            }
        }
        return redirect()->back()->with('success', $i . ' Jauges ajouter avec succès.');
    }


    public function ajouter_jauge(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date'],
            'bus' => ['required', 'exists:buses,id'],
            'brigade' => ['required'],
            'nomvidange' => ['required'],
            'equipe' => 'nullable|array',
        ]);
        $pieces = $request->input('pieces', []);
        $quantities = $request->input('piece_quantities', []);
        $mergedPieces = [];
        foreach ($pieces as $index => $pieceId) {
            if (isset($quantities[$index])) {
                $mergedPieces[$pieceId] = $quantities[$index];
            }
        }
        $ficheData = [
            'user_id' => Auth::user()->id,
            'date_fiche' => $request['date'],
            'declaré' => false,
            'id_bus' => $request['bus'],
            'id_ligne' => null,
            'brigade' => $request->brigade,
            'id_chauffeur' => null,
            'heur_depart' => "00:00",
            'heur_arrive' => "00:00",
            'gasoile' => "0",
            'kmdepart' => "0",
            'kmarrive' => "0",
            'kmhlp' => "0",
            'kmgobale' => "0",
            'kmcommerciale' => "0",
        ];
        $fiche = fichemaintenance::create($ficheData);
        $fichepanne_data = [
            'fichemaintenance_id' => $fiche->id,
            'pannnename_id' => $request->nomvidange,
            'solved' => true,
            'date_resoudre' => $request->date,
            'lieu_resoudre' => 'Depot',
            'brigade' => $request->brigade,
            'equipe' => $request->equipe ? json_encode($request->equipe) : null,
            'description' => $request->description,
        ];
        $fichepanne = fichepanne_model::create($fichepanne_data);
        if ($mergedPieces) {
            foreach ($mergedPieces as $pieceId => $quantity) {
                used_pieces::create(
                    [
                        'fichepanne_id' => $fichepanne->id,
                        'piece_id' => $pieceId,
                        'quantité' => $quantity,
                    ]
                );
            }
        }
        // $ficheData = [
        //     'user_id' => Auth::user()->id,
        //     'date_fiche' => $request['date'],
        //     'declaré' => false,
        //     'id_bus' => $request['bus'],
        //     'brigade' => $request->brigade,
        //     'kmarrive' => "0",
        //     'kmhlp' => "0",
        //     'kmgobale' => "0",
        //     'kmcommerciale' => "0",
        // ];
        // $fiche = fichemaintenance::create($ficheData);
        // $fichepanne_data = [
        //     'fichemaintenance_id' => $fiche->id,
        //     'pannnename_id' => $request->nomvidange,
        //     'solved' => true,
        //     'date_resoudre' => $request->date,
        //     'lieu_resoudre' => 'Depot',
        //     'brigade' => $request->brigade,
        //     'equipe' => $request->equipe ? json_encode($request->equipe) : null,
        //     'description' => $request->description,
        // ];
        // $fichepanne = fichepanne_model::create($fichepanne_data);
        // if ($mergedPieces) {
        //     foreach ($mergedPieces as $pieceId => $quantity) {
        //         used_pieces::create(
        //             [
        //                 'fichepanne_id' => $fichepanne->id,
        //                 'piece_id' => $pieceId,
        //                 'quantité' => $quantity,
        //             ]
        //         );
        //     }
        // }
        return redirect()->back()->with('success', 'Jauge ajouter avec succès.');
    }
    public function maintenance_panne()
    {
        $pannes = fichepanne_model::where('solved', 0)->get();
        $pannesresolue = fichepanne_model::where('solved', 1)->whereBetween('date_resoudre', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->endOfMonth()])->with(['fichemaintenance.chauffeur'])->get();
        // $agents = maintenance_agent::all();
        // $stations = Station::all();
        // $pieces = pieces_maintanance::all();
        // $buses = Bus::all();
        // $pannenames = Panne::all();
        $agents = maintenance_agent::select('id', 'firstname', 'lastname')->get();
        $stations = Station::select('id', 'name')->get();
        $pieces = pieces_maintanance::select('id', 'name')->get();
        $buses = Bus::select('id', 'name')->get();
        $pannenames = Panne::select('id', 'name')->get();
        return view('maintenance.maintenancepanne', compact(['pannes', 'pannesresolue', 'agents', 'stations', 'pieces', 'buses', 'pannenames']));
    }
    public function ajouter_ndpanne(Request $request)
    {
        $ispannemecanique = $request->input('pannemecaniquecheck') === 'on';
        $ispannelectrique = $request->input('panneelectriquecheck') === 'on';
        $ispannetolle = $request->input('pannetollecheck') === 'on';
        $request->validate([
            'date' => ['required', 'date'],
            'bus' => ['required', 'exists:buses,id'],
            'id_chauffeur' => 'nullable',
            'pannemecanique' => !$ispannemecanique ? ['nullable'] :  'required|array',
            'panneelectrique' => !$ispannelectrique ? ['nullable'] :  'required|array',
            'pannetolle' => !$ispannetolle ? ['nullable'] :  'required|array',
        ]);

        $ficheData = [
            'user_id' => Auth::user()->id,
            'date_fiche' => $request['date'],
            'declaré' => false,
            'id_bus' => $request['bus'],
            'id_ligne' => null,
            'brigade' => null,
            'id_chauffeur' => null,
            'heur_depart' => "00:00",
            'heur_arrive' => "00:00",
            'gasoile' => "0",
            'kmdepart' => "0",
            'kmarrive' => "0",
            'kmhlp' => "0",
            'kmgobale' => "0",
            'kmcommerciale' => "0",
        ];

        $fiche = fichemaintenance::create($ficheData);
        $panneTypes = ['pannemecanique', 'panneelectrique', 'pannetolle'];
        foreach ($panneTypes as $panneType) {
            if (!empty($request[$panneType])) {
                foreach ($request[$panneType] as $panneId) {
                    fichepanne_model::create([
                        'fichemaintenance_id' => $fiche->id,
                        'pannnename_id' => $panneId,
                        'solved' => false,
                    ]);
                }
            }
        }
        return redirect()->back()->with('success', 'Panne ajouter avec succès.');
    }
    public function resoudre_maintenance_panne(resoudre_panne_request $request)
    {
        $pieces = $request->input('pieces', []);
        $quantities = $request->input('piece_quantities', []);
        $mergedPieces = [];
        foreach ($pieces as $index => $pieceId) {
            if (isset($quantities[$index])) {
                $mergedPieces[$pieceId] = $quantities[$index];
            }
        }
        $fichepanne = fichepanne_model::find($request->input('fichepanne_id'));
        if ($fichepanne) {
            $fichepanne->update([
                'solved' => true,
                'date_resoudre' => $request->input('dateresolu'),
                'lieu_resoudre' =>  $request->input('lieuresolu'),
                'brigade' => $request->input('brigade'),
                'equipe' => $request->input('equipe'),
                'delai' => intval($request->duree),
                'description' => $request->input('description'),
            ]);
            if ($mergedPieces) {
                foreach ($mergedPieces as $pieceId => $quantity) {
                    used_pieces::create(
                        [
                            'fichepanne_id' => $request->input('fichepanne_id'),
                            'piece_id' => $pieceId,
                            'quantité' => $quantity,
                        ]
                    );
                }
            }
        }
        return redirect()->back()->with('success', 'Panne résolue avec succès.');
    }

    public function statistiques_maintenance()
    {
        $buses = Bus::whereIn('type', ['v8', 'l5'])->get();
        $pieces = pieces_maintanance::all();
        $pannes = fichepanne_model::get(['equipe', 'date_resoudre']);
        $traveaux = traveauxlibre_model::get(['equipe', 'date_resoudre']);
        $agents = maintenance_agent::all();
        $equipes = $pannes->pluck('equipe');
        $equipesTraveaux = $traveaux->pluck('equipe');
        $equipesFusionnees = $equipes->merge($equipesTraveaux);
        $equipesUniques = $equipesFusionnees
            ->filter()
            ->map(function ($equipe) {
                $decoded = json_decode($equipe, true);
                if (is_array($decoded)) {
                    sort($decoded);
                    return json_encode($decoded);
                }
                return null;
            })
            ->filter()
            ->unique()
            ->values();
        return view('maintenance.statistiques_maintenance', compact(['buses', 'pieces', 'equipesUniques', 'agents']));
    }

    public function statistiques_data(Request $request)
    {
        $request->validate([
            'data_type' => 'required',
            // 'month' => 'required',
            'year' => 'required',
            'piece' => 'required',
        ]);
        $month = $request->month;
        $year = $request->year;
        $piece = $request->piece;
        $firstDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
        $lastDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');
        $data = [];
        if ($request->data_type == 'simple_parbus') {
            if ($piece == 'Gasoile') {
                $query = bus::query()
                    ->whereIn('type', ['v8', 'l5'])
                    ->leftJoin('fiches_maintenance', function ($join) use ($firstDay, $lastDay) {
                        $join->on('buses.id', '=', 'fiches_maintenance.id_bus')
                            ->where('fiches_maintenance.date_fiche', '>=', $firstDay)
                            ->where('fiches_maintenance.date_fiche', '<=', $lastDay)
                            ->where('fiches_maintenance.declaré', '=', true)
                            ->where(function ($q) {
                                $q->where('fiches_maintenance.brigade', 'soir')
                                    ->orWhere('fiches_maintenance.brigade', 'matin');
                            });
                    })
                    ->selectRaw('
            buses.id as id_bus, 
            buses.name as name_bus, 
            COALESCE(SUM(fiches_maintenance.gasoile), 0) as total_gasoile
        ')
                    ->groupBy('buses.id', 'buses.name')
                    ->orderBy('buses.id');


                $data = $query->get();
            } elseif ($piece == 'Kilometrage') {
                $query = bus::query()
                    ->whereIn('type', ['v8', 'l5'])
                    ->leftJoin('fiches_maintenance', function ($join) use ($firstDay, $lastDay) {
                        $join->on('buses.id', '=', 'fiches_maintenance.id_bus')
                            ->where('fiches_maintenance.date_fiche', '>=', $firstDay)
                            ->where('fiches_maintenance.date_fiche', '<=', $lastDay)
                            ->where('fiches_maintenance.declaré', '=', true)
                            ->where(function ($q) {
                                $q->where('fiches_maintenance.brigade', 'soir')
                                    ->orWhere('fiches_maintenance.brigade', 'matin');
                            });
                    })
                    ->selectRaw('
            buses.id as id_bus, 
            buses.name as name_bus, 
            COALESCE(SUM(fiches_maintenance.kmgobale), 0) as total_gasoile
        ')
                    ->groupBy('buses.id', 'buses.name')
                    ->orderBy('buses.id');
                $data = $query->get();
            } elseif ($piece == 'Gasoile/100') {
                $query = bus::query()
                    ->whereIn('type', ['v8', 'l5'])
                    ->leftJoin('fiches_maintenance', function ($join) use ($firstDay, $lastDay) {
                        $join->on('buses.id', '=', 'fiches_maintenance.id_bus')
                            ->where('fiches_maintenance.date_fiche', '>=', $firstDay)
                            ->where('fiches_maintenance.date_fiche', '<=', $lastDay)
                            ->where('fiches_maintenance.declaré', '=', true)
                            ->where(function ($q) {
                                $q->where('fiches_maintenance.brigade', 'soir')
                                    ->orWhere('fiches_maintenance.brigade', 'matin');
                            });
                    })
                    ->selectRaw('
            buses.id as id_bus, 
            buses.name as name_bus, 
            (COALESCE(SUM(fiches_maintenance.gasoile), 0)*100)/COALESCE(SUM(fiches_maintenance.kmgobale), 0) as total_gasoile
        ')
                    ->groupBy('buses.id', 'buses.name')
                    ->orderBy('buses.id');
                $data = $query->get();
            } elseif ($piece == 'Huile 15w40') {
                $query = bus::query()
                    ->whereIn('type', ['v8', 'l5'])
                    ->leftJoin('fiches_maintenance', 'fiches_maintenance.id_bus', '=', 'buses.id')
                    ->leftJoin('fichepanne', function ($join) use ($firstDay, $lastDay) {
                        $join->on('fiches_maintenance.id', '=', 'fichepanne.fichemaintenance_id')
                            ->where('fichepanne.date_resoudre', '>=', $firstDay)
                            ->where('fichepanne.date_resoudre', '<=', $lastDay);
                    })
                    ->leftJoin('used_pieces', 'fichepanne.id', '=', 'used_pieces.fichepanne_id')
                    ->where('used_pieces.piece_id', '=', 2)
                    ->whereNull('used_pieces.deleted_at')
                    ->selectRaw('
                    buses.id as id_bus, 
                    buses.name as name_bus, 
                    COALESCE(SUM(used_pieces.quantité), 0) as total_gasoile
                ')
                    ->groupBy('buses.id', 'buses.name')
                    ->orderBy('buses.id');

                $data = $query->get();
                $query2 = bus::query()
                    ->whereIn('type', ['v8', 'l5'])
                    ->leftJoin('traveauxlibre', function ($join) use ($firstDay, $lastDay) {
                        $join->on('traveauxlibre.id_bus', '=', 'buses.id')
                            ->where('traveauxlibre.date_resoudre', '>=', $firstDay)
                            ->where('traveauxlibre.date_resoudre', '<=', $lastDay);
                    })
                    ->leftJoin('traveauxlibreusedpieces', 'traveauxlibre.id', '=', 'traveauxlibreusedpieces.traveauxlibre_id')
                    ->where('traveauxlibreusedpieces.piece_id', '=', 2)
                    ->selectRaw('
                    buses.id as id_bus, 
                    buses.name as name_bus, 
                    COALESCE(SUM(traveauxlibreusedpieces.quantité), 0) as total_gasoile
                ')
                    ->groupBy('buses.id', 'buses.name')
                    ->orderBy('buses.id');

                $data2 = $query2->get();
                $allbuses = Bus::whereIn('type', ['v8', 'l5'])->selectRaw('
                    id as id_bus, 
                    name as name_bus, 
                    0 as total_gasoile')->get();

                $mergedData = collect();

                foreach ($allbuses as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data2 as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                $data = $mergedData->sortBy('id_bus')->values();
            } elseif ($piece == 'Huile 15w40/Sans vidange') {
                $query = bus::query()
                    ->whereIn('type', ['v8', 'l5'])
                    ->leftJoin('fiches_maintenance', 'fiches_maintenance.id_bus', '=', 'buses.id')
                    ->leftJoin('fichepanne', function ($join) use ($firstDay, $lastDay) {
                        $join->on('fiches_maintenance.id', '=', 'fichepanne.fichemaintenance_id')
                            ->where('fichepanne.date_resoudre', '>=', $firstDay)
                            ->where('fichepanne.date_resoudre', '<=', $lastDay)
                            ->whereNotIn('fichepanne.pannnename_id', [23, 24, 25]);
                    })
                    ->leftJoin('used_pieces', 'fichepanne.id', '=', 'used_pieces.fichepanne_id')
                    ->where('used_pieces.piece_id', '=', 2)
                    ->whereNull('used_pieces.deleted_at')

                    ->selectRaw('
                    buses.id as id_bus, 
                    buses.name as name_bus, 
                    COALESCE(SUM(used_pieces.quantité), 0) as total_gasoile
                ')
                    ->groupBy('buses.id', 'buses.name')
                    ->orderBy('buses.id');
                $data = $query->get();
                $query2 = bus::query()
                    ->whereIn('type', ['v8', 'l5'])
                    ->leftJoin('traveauxlibre', function ($join) use ($firstDay, $lastDay) {
                        $join->on('traveauxlibre.id_bus', '=', 'buses.id')
                            ->where('traveauxlibre.date_resoudre', '>=', $firstDay)
                            ->where('traveauxlibre.date_resoudre', '<=', $lastDay);
                    })
                    ->leftJoin('traveauxlibreusedpieces', 'traveauxlibre.id', '=', 'traveauxlibreusedpieces.traveauxlibre_id')
                    ->where('traveauxlibreusedpieces.piece_id', '=', 2)
                    ->selectRaw('
                    buses.id as id_bus, 
                    buses.name as name_bus, 
                    COALESCE(SUM(traveauxlibreusedpieces.quantité), 0) as total_gasoile
                ')
                    ->groupBy('buses.id', 'buses.name')
                    ->orderBy('buses.id');

                $data2 = $query2->get();
                $allbuses = Bus::whereIn('type', ['v8', 'l5'])->selectRaw('
                    id as id_bus, 
                    name as name_bus, 
                    0 as total_gasoile')->get();

                $mergedData = collect();

                foreach ($allbuses as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data2 as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                $data = $mergedData->sortBy('id_bus')->values();
            } elseif ($piece == 'Glaciole') {
                $query = bus::query()
                    ->whereIn('type', ['v8', 'l5'])
                    ->leftJoin('fiches_maintenance', 'fiches_maintenance.id_bus', '=', 'buses.id')
                    ->leftJoin('fichepanne', function ($join) use ($firstDay, $lastDay) {
                        $join->on('fiches_maintenance.id', '=', 'fichepanne.fichemaintenance_id')
                            ->where('fichepanne.date_resoudre', '>=', $firstDay)
                            ->where('fichepanne.date_resoudre', '<=', $lastDay)
                            ->whereNotIn('fichepanne.pannnename_id', [23, 24, 25]);
                    })
                    ->leftJoin('used_pieces', 'fichepanne.id', '=', 'used_pieces.fichepanne_id')
                    ->where('used_pieces.piece_id', '=', 9)
                    ->whereNull('used_pieces.deleted_at')

                    ->selectRaw('
                    buses.id as id_bus, 
                    buses.name as name_bus, 
                    COALESCE(SUM(used_pieces.quantité), 0) as total_gasoile
                ')
                    ->groupBy('buses.id', 'buses.name')
                    ->orderBy('buses.id');
                $data = $query->get();
                $query2 = bus::query()
                    ->whereIn('type', ['v8', 'l5'])
                    ->leftJoin('traveauxlibre', function ($join) use ($firstDay, $lastDay) {
                        $join->on('traveauxlibre.id_bus', '=', 'buses.id')
                            ->where('traveauxlibre.date_resoudre', '>=', $firstDay)
                            ->where('traveauxlibre.date_resoudre', '<=', $lastDay);
                    })
                    ->leftJoin('traveauxlibreusedpieces', 'traveauxlibre.id', '=', 'traveauxlibreusedpieces.traveauxlibre_id')
                    ->where('traveauxlibreusedpieces.piece_id', '=', 9)
                    ->selectRaw('
                    buses.id as id_bus, 
                    buses.name as name_bus, 
                    COALESCE(SUM(traveauxlibreusedpieces.quantité), 0) as total_gasoile
                ')
                    ->groupBy('buses.id', 'buses.name')
                    ->orderBy('buses.id');

                $data2 = $query2->get();
                $allbuses = Bus::whereIn('type', ['v8', 'l5'])->selectRaw('
                    id as id_bus, 
                    name as name_bus, 
                    0 as total_gasoile')->get();

                $mergedData = collect();

                foreach ($allbuses as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data2 as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                $data = $mergedData->sortBy('id_bus')->values();
            } elseif (filter_var($piece, FILTER_VALIDATE_INT) !== false) {
                $query = bus::query()
                    ->whereIn('type', ['v8', 'l5'])
                    ->leftJoin('fiches_maintenance', 'fiches_maintenance.id_bus', '=', 'buses.id')
                    ->leftJoin('fichepanne', function ($join) use ($firstDay, $lastDay) {
                        $join->on('fiches_maintenance.id', '=', 'fichepanne.fichemaintenance_id')
                            ->where('fichepanne.date_resoudre', '>=', $firstDay)
                            ->where('fichepanne.date_resoudre', '<=', $lastDay);
                    })
                    ->leftJoin('used_pieces', 'fichepanne.id', '=', 'used_pieces.fichepanne_id')
                    ->where('used_pieces.piece_id', '=', $piece)
                    ->whereNull('used_pieces.deleted_at')
                    ->selectRaw('
                    buses.id as id_bus, 
                    buses.name as name_bus, 
                    COALESCE(SUM(used_pieces.quantité), 0) as total_gasoile
                ')
                    ->groupBy('buses.id', 'buses.name')
                    ->orderBy('buses.id');

                $data = $query->get();
                $query2 = bus::query()
                    ->whereIn('type', ['v8', 'l5'])
                    ->leftJoin('traveauxlibre', function ($join) use ($firstDay, $lastDay) {
                        $join->on('traveauxlibre.id_bus', '=', 'buses.id')
                            ->where('traveauxlibre.date_resoudre', '>=', $firstDay)
                            ->where('traveauxlibre.date_resoudre', '<=', $lastDay);
                    })
                    ->leftJoin('traveauxlibreusedpieces', 'traveauxlibre.id', '=', 'traveauxlibreusedpieces.traveauxlibre_id')
                    ->where('traveauxlibreusedpieces.piece_id', '=', $piece)
                    ->selectRaw('
                    buses.id as id_bus, 
                    buses.name as name_bus, 
                    COALESCE(SUM(traveauxlibreusedpieces.quantité), 0) as total_gasoile
                ')
                    ->groupBy('buses.id', 'buses.name')
                    ->orderBy('buses.id');

                $data2 = $query2->get();
                $allbuses = Bus::whereIn('type', ['v8', 'l5'])->selectRaw('
                    id as id_bus, 
                    name as name_bus, 
                    0 as total_gasoile')->get();

                $mergedData = collect();

                foreach ($allbuses as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data2 as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                $data = $mergedData->sortBy('id_bus')->values();
            }
        } elseif ($request->data_type == 'traveaux_bus_mois') {
            if ($piece == 'Pannes Déclarés') {
                $query = bus::query()
                    ->whereIn('buses.type', ['v8', 'l5'])
                    ->leftJoin('fiches_maintenance', function ($join) use ($firstDay, $lastDay) {
                        $join->on('buses.id', '=', 'fiches_maintenance.id_bus')
                            ->whereBetween('fiches_maintenance.date_fiche', [$firstDay, $lastDay])
                            ->where('fiches_maintenance.declaré', true);
                    })
                    ->leftjoin('fichepanne', 'fichepanne.fichemaintenance_id', '=', 'fiches_maintenance.id')
                    ->whereNull('fichepanne.deleted_at')
                    ->leftjoin('pannenames', 'fichepanne.pannnename_id', '=', 'pannenames.id')
                    ->selectRaw('
                    buses.id as id_bus, 
                    buses.name as name_bus, 
                    COUNT(CASE WHEN pannenames.type = "electrique" THEN 1 END) as total_electrique,
                    COUNT(CASE WHEN pannenames.type = "tolle" THEN 1 END) as total_tolle,
                    COUNT(CASE WHEN pannenames.type = "mecanique" THEN 1 END) as total_moteur
                ')
                    ->groupBy('buses.id', 'buses.name')
                    ->orderBy('buses.id');
                $data = $query->get();
            } elseif ($piece == 'Traveaux libre') {
                $query = bus::query()
                    ->whereIn('buses.type', ['v8', 'l5'])
                    ->leftJoin('traveauxlibre', function ($join) use ($firstDay, $lastDay) {
                        $join->on('traveauxlibre.id_bus', '=', 'buses.id')
                            ->whereBetween('traveauxlibre.date_resoudre', [$firstDay, $lastDay]);
                    })
                    ->selectRaw('
                    buses.id as id_bus, 
                    buses.name as name_bus, 
                    COUNT(CASE WHEN traveauxlibre.nature = "electrique" THEN 1 END) as total_electrique,
                    COUNT(CASE WHEN traveauxlibre.nature = "tolle" THEN 1 END) as total_tolle,
                    COUNT(CASE WHEN traveauxlibre.nature = "mecanique" THEN 1 END) as total_moteur
                ')
                    ->groupBy('buses.id', 'buses.name')
                    ->orderBy('buses.id');
                $data = $query->get();
            }
        } elseif ($request->data_type == 'ligne_bus_mois') {
            if ($piece == 'Kilometrage') {
                $query = fichemaintenance::selectRaw("
                DATE_FORMAT(date_fiche, '%Y-%m') as month, 
                SUM(kmgobale) as total
                ")
                    ->where('id_bus', $request->bus)
                    ->where('declaré', true)
                    ->whereYear('date_fiche', $year)
                    ->groupBy('month')
                    ->orderBy('month', 'asc');

                $data = $query->get();
            } elseif ($piece == 'Gasoile') {
                $query = fichemaintenance::selectRaw("
                DATE_FORMAT(date_fiche, '%Y-%m') as month, 
                SUM(gasoile) as total
                ")
                    ->where('id_bus', $request->bus)
                    ->where('declaré', true)
                    ->whereYear('date_fiche', $year)
                    ->groupBy('month')
                    ->orderBy('month', 'asc');

                $data = $query->get();
            } elseif ($piece == 'Gasoile/100') {
                $query = fichemaintenance::selectRaw("
                    DATE_FORMAT(date_fiche, '%Y-%m') as month, 
                    COALESCE((SUM(gasoile) * 100) / NULLIF(SUM(kmgobale), 0), 0) as total
                ")
                    ->where('id_bus', $request->bus)
                    ->where('declaré', true)
                    ->whereYear('date_fiche', $year)
                    ->groupBy('month')
                    ->orderBy('month', 'asc');
                $data = $query->get();
            } elseif ($piece == 'Huile 15w40') {
                $month = $request->month;
                $year = $request->year;
                $piece = $request->piece;
                $firstDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->startOfYear()->format('Y-m-d');
                $lastDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->endOfYear()->format('Y-m-d');
                $query = bus::query()
                    ->where('buses.id', $request->bus)
                    ->leftJoin('fiches_maintenance', 'fiches_maintenance.id_bus', '=', 'buses.id')
                    ->leftJoin('fichepanne', 'fichepanne.fichemaintenance_id', '=', 'fiches_maintenance.id')
                    ->leftJoin('used_pieces', 'used_pieces.fichepanne_id', '=', 'fichepanne.id')
                    ->where('fichepanne.date_resoudre', '>=', $firstDay)
                    ->where('fichepanne.date_resoudre', '<=', $lastDay)
                    ->whereNull('used_pieces.deleted_at')
                    ->where('used_pieces.piece_id', '=', 2)
                    ->selectRaw("
                        DATE_FORMAT(fichepanne.date_resoudre, '%Y-%m') as month,
                        COALESCE(SUM(used_pieces.quantité), 0) as total
                    ")
                    ->groupBy('month', 'buses.id', 'buses.name')
                    ->orderBy('month', 'asc');
                $data = $query->get();
                $query2 = bus::query()
                    ->where('buses.id', $request->bus)
                    ->leftJoin('traveauxlibre', 'traveauxlibre.id_bus', '=', 'buses.id')
                    ->leftJoin('traveauxlibreusedpieces', 'traveauxlibreusedpieces.traveauxlibre_id', '=', 'traveauxlibre.id')
                    ->where('traveauxlibre.date_resoudre', '>=', $firstDay)
                    ->where('traveauxlibre.date_resoudre', '<=', $lastDay)
                    ->where('traveauxlibreusedpieces.piece_id', '=', 2)
                    ->selectRaw("
                DATE_FORMAT(traveauxlibre.date_resoudre, '%Y-%m') as month,
                COALESCE(SUM(traveauxlibreusedpieces.quantité), 0) as total
                ")
                    ->groupBy('month', 'buses.id', 'buses.name')
                    ->orderBy('month', 'asc');
                $data2 = $query2->get();
                $mergedData = collect();

                foreach ($data as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data2 as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                $data = $mergedData->sortBy('id_bus')->values();
            } elseif ($piece == 'Huile 15w40/Sans vidange') {
                $month = $request->month;
                $year = $request->year;
                $piece = $request->piece;
                $firstDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->startOfYear()->format('Y-m-d');
                $lastDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->endOfYear()->format('Y-m-d');
                $query = bus::query()
                    ->where('buses.id', $request->bus)
                    ->leftJoin('fiches_maintenance', 'fiches_maintenance.id_bus', '=', 'buses.id')
                    ->leftJoin('fichepanne', 'fichepanne.fichemaintenance_id', '=', 'fiches_maintenance.id')
                    ->leftJoin('used_pieces', 'used_pieces.fichepanne_id', '=', 'fichepanne.id')
                    ->where('fichepanne.date_resoudre', '>=', $firstDay)
                    ->where('fichepanne.date_resoudre', '<=', $lastDay)
                    ->whereNull('used_pieces.deleted_at')
                    ->where('used_pieces.piece_id', '=', 2)
                    ->whereNotIn('fichepanne.pannnename_id', [23, 24, 25])
                    ->selectRaw("
                        DATE_FORMAT(fichepanne.date_resoudre, '%Y-%m') as month,
                        COALESCE(SUM(used_pieces.quantité), 0) as total
                    ")
                    ->groupBy('month', 'buses.id', 'buses.name')
                    ->orderBy('month', 'asc');
                $data = $query->get();
                $query2 = bus::query()
                    ->where('buses.id', $request->bus)
                    ->leftJoin('traveauxlibre', 'traveauxlibre.id_bus', '=', 'buses.id')
                    ->leftJoin('traveauxlibreusedpieces', 'traveauxlibreusedpieces.traveauxlibre_id', '=', 'traveauxlibre.id')
                    ->where('traveauxlibre.date_resoudre', '>=', $firstDay)
                    ->where('traveauxlibre.date_resoudre', '<=', $lastDay)
                    ->where('traveauxlibreusedpieces.piece_id', '=', 2)
                    ->selectRaw("
                DATE_FORMAT(traveauxlibre.date_resoudre, '%Y-%m') as month,
                COALESCE(SUM(traveauxlibreusedpieces.quantité), 0) as total
                ")
                    ->groupBy('month', 'buses.id', 'buses.name')
                    ->orderBy('month', 'asc');
                $data2 = $query2->get();
                $mergedData = collect();

                foreach ($data as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data2 as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                $data = $mergedData->sortBy('id_bus')->values();
            } elseif ($piece == 'Glaciole') {
                $month = $request->month;
                $year = $request->year;
                $piece = $request->piece;
                $firstDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->startOfYear()->format('Y-m-d');
                $lastDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->endOfYear()->format('Y-m-d');
                $query = bus::query()
                    ->where('buses.id', $request->bus)
                    ->leftJoin('fiches_maintenance', 'fiches_maintenance.id_bus', '=', 'buses.id')
                    ->leftJoin('fichepanne', 'fichepanne.fichemaintenance_id', '=', 'fiches_maintenance.id')
                    ->leftJoin('used_pieces', 'used_pieces.fichepanne_id', '=', 'fichepanne.id')
                    ->where('fichepanne.date_resoudre', '>=', $firstDay)
                    ->where('fichepanne.date_resoudre', '<=', $lastDay)
                    ->whereNull('used_pieces.deleted_at')
                    ->where('used_pieces.piece_id', '=', 9)
                    ->whereNotIn('fichepanne.pannnename_id', [23, 24, 25])
                    ->selectRaw("
                        DATE_FORMAT(fichepanne.date_resoudre, '%Y-%m') as month,
                        COALESCE(SUM(used_pieces.quantité), 0) as total
                    ")
                    ->groupBy('month', 'buses.id', 'buses.name')
                    ->orderBy('month', 'asc');
                $data = $query->get();
                $query2 = bus::query()
                    ->where('buses.id', $request->bus)
                    ->leftJoin('traveauxlibre', 'traveauxlibre.id_bus', '=', 'buses.id')
                    ->leftJoin('traveauxlibreusedpieces', 'traveauxlibreusedpieces.traveauxlibre_id', '=', 'traveauxlibre.id')
                    ->where('traveauxlibre.date_resoudre', '>=', $firstDay)
                    ->where('traveauxlibre.date_resoudre', '<=', $lastDay)
                    ->where('traveauxlibreusedpieces.piece_id', '=', 9)
                    ->selectRaw("
                DATE_FORMAT(traveauxlibre.date_resoudre, '%Y-%m') as month,
                COALESCE(SUM(traveauxlibreusedpieces.quantité), 0) as total
                ")
                    ->groupBy('month', 'buses.id', 'buses.name')
                    ->orderBy('month', 'asc');
                $data2 = $query2->get();
                $mergedData = collect();

                foreach ($data as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data2 as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                $data = $mergedData->sortBy('id_bus')->values();
            } elseif (filter_var($piece, FILTER_VALIDATE_INT) !== false) {
                $month = $request->month;
                $year = $request->year;
                $piece = $request->piece;
                $firstDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->startOfYear()->format('Y-m-d');
                $lastDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->endOfYear()->format('Y-m-d');
                $query = bus::query()
                    ->where('buses.id', $request->bus)
                    ->leftJoin('fiches_maintenance', 'fiches_maintenance.id_bus', '=', 'buses.id')
                    ->leftJoin('fichepanne', 'fichepanne.fichemaintenance_id', '=', 'fiches_maintenance.id')
                    ->leftJoin('used_pieces', 'used_pieces.fichepanne_id', '=', 'fichepanne.id')
                    ->where('fichepanne.date_resoudre', '>=', $firstDay)
                    ->where('fichepanne.date_resoudre', '<=', $lastDay)
                    ->whereNull('used_pieces.deleted_at')
                    ->where('used_pieces.piece_id', '=', $piece)
                    // ->whereNotIn('fichepanne.pannnename_id', [23, 24, 25])
                    ->selectRaw("
                        DATE_FORMAT(fichepanne.date_resoudre, '%Y-%m') as month,
                        COALESCE(SUM(used_pieces.quantité), 0) as total
                    ")
                    ->groupBy('month', 'buses.id', 'buses.name')
                    ->orderBy('month', 'asc');
                $data = $query->get();
                $query2 = bus::query()
                    ->where('buses.id', $request->bus)
                    ->leftJoin('traveauxlibre', 'traveauxlibre.id_bus', '=', 'buses.id')
                    ->leftJoin('traveauxlibreusedpieces', 'traveauxlibreusedpieces.traveauxlibre_id', '=', 'traveauxlibre.id')
                    ->where('traveauxlibre.date_resoudre', '>=', $firstDay)
                    ->where('traveauxlibre.date_resoudre', '<=', $lastDay)
                    ->where('traveauxlibreusedpieces.piece_id', '=', $piece)
                    ->selectRaw("
                DATE_FORMAT(traveauxlibre.date_resoudre, '%Y-%m') as month,
                COALESCE(SUM(traveauxlibreusedpieces.quantité), 0) as total
                ")
                    ->groupBy('month', 'buses.id', 'buses.name')
                    ->orderBy('month', 'asc');
                $data2 = $query2->get();
                $mergedData = collect();

                foreach ($data as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data2 as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                $data = $mergedData->sortBy('id_bus')->values();
            }
        } elseif ($request->data_type == 'ligne_piece_mois') {
            if ($piece == 'Kilometrage') {
                $query = fichemaintenance::selectRaw("
                DATE_FORMAT(date_fiche, '%Y-%m') as month, 
                SUM(kmgobale) as total
                ")
                    ->where('declaré', true)
                    ->whereYear('date_fiche', $year)
                    ->groupBy('month')
                    ->orderBy('month', 'asc');

                $data = $query->get();
            } elseif ($piece == 'Gasoile') {
                $query = fichemaintenance::selectRaw("
                DATE_FORMAT(date_fiche, '%Y-%m') as month, 
                SUM(gasoile) as total
                ")
                    ->where('declaré', true)
                    ->whereYear('date_fiche', $year)
                    ->groupBy('month')
                    ->orderBy('month', 'asc');

                $data = $query->get();
            } elseif ($piece == 'Huile 15w40/Sans vidange') {
                $month = $request->month;
                $year = $request->year;
                $piece = $request->piece;
                $firstDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->startOfYear()->format('Y-m-d');
                $lastDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->endOfYear()->format('Y-m-d');
                $query = fichemaintenance::query()
                    ->leftJoin('fichepanne', 'fichepanne.fichemaintenance_id', '=', 'fiches_maintenance.id')
                    ->leftJoin('used_pieces', 'used_pieces.fichepanne_id', '=', 'fichepanne.id')
                    ->where('fichepanne.date_resoudre', '>=', $firstDay)
                    ->where('fichepanne.date_resoudre', '<=', $lastDay)
                    ->whereNull('used_pieces.deleted_at')
                    ->where('used_pieces.piece_id', '=', 2)
                    ->whereNotIn('fichepanne.pannnename_id', [23, 24, 25])
                    ->selectRaw("
                        DATE_FORMAT(fichepanne.date_resoudre, '%Y-%m') as month,
                        COALESCE(SUM(used_pieces.quantité), 0) as total
                    ")
                    ->groupBy('month')
                    ->orderBy('month', 'asc');
                $data = $query->get();
                $query2 = traveauxlibre_model::query()
                    ->leftJoin('traveauxlibreusedpieces', 'traveauxlibreusedpieces.traveauxlibre_id', '=', 'traveauxlibre.id')
                    ->where('traveauxlibre.date_resoudre', '>=', $firstDay)
                    ->where('traveauxlibre.date_resoudre', '<=', $lastDay)
                    ->where('traveauxlibreusedpieces.piece_id', '=', 2)
                    ->selectRaw("
                DATE_FORMAT(traveauxlibre.date_resoudre, '%Y-%m') as month,
                COALESCE(SUM(traveauxlibreusedpieces.quantité), 0) as total
                ")
                    ->groupBy('month')
                    ->orderBy('month', 'asc');
                $data2 = $query2->get();
                $mergedData = collect();

                foreach ($data as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data2 as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                $data = $mergedData->values();
            } elseif ($piece == 'Glaciole') {
                $month = $request->month;
                $year = $request->year;
                $piece = $request->piece;
                $firstDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->startOfYear()->format('Y-m-d');
                $lastDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->endOfYear()->format('Y-m-d');
                $query = fichemaintenance::query()
                    ->leftJoin('fichepanne', 'fichepanne.fichemaintenance_id', '=', 'fiches_maintenance.id')
                    ->leftJoin('used_pieces', 'used_pieces.fichepanne_id', '=', 'fichepanne.id')
                    ->where('fichepanne.date_resoudre', '>=', $firstDay)
                    ->where('fichepanne.date_resoudre', '<=', $lastDay)
                    ->whereNull('used_pieces.deleted_at')
                    ->where('used_pieces.piece_id', '=', 9)
                    ->whereNotIn('fichepanne.pannnename_id', [23, 24, 25])
                    ->selectRaw("
                        DATE_FORMAT(fichepanne.date_resoudre, '%Y-%m') as month,
                        COALESCE(SUM(used_pieces.quantité), 0) as total
                    ")
                    ->groupBy('month')
                    ->orderBy('month', 'asc');
                $data = $query->get();
                $query2 = traveauxlibre_model::query()
                    ->leftJoin('traveauxlibreusedpieces', 'traveauxlibreusedpieces.traveauxlibre_id', '=', 'traveauxlibre.id')
                    ->where('traveauxlibre.date_resoudre', '>=', $firstDay)
                    ->where('traveauxlibre.date_resoudre', '<=', $lastDay)
                    ->where('traveauxlibreusedpieces.piece_id', '=', 9)
                    ->selectRaw("
                DATE_FORMAT(traveauxlibre.date_resoudre, '%Y-%m') as month,
                COALESCE(SUM(traveauxlibreusedpieces.quantité), 0) as total
                ")
                    ->groupBy('month')
                    ->orderBy('month', 'asc');
                $data2 = $query2->get();
                $mergedData = collect();

                foreach ($data as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data2 as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                $data = $mergedData->values();
            } elseif (filter_var($piece, FILTER_VALIDATE_INT) !== false) {
                $month = $request->month;
                $year = $request->year;
                $piece = $request->piece;
                $firstDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->startOfYear()->format('Y-m-d');
                $lastDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->endOfYear()->format('Y-m-d');
                $query = fichemaintenance::query()
                    ->leftJoin('fichepanne', 'fichepanne.fichemaintenance_id', '=', 'fiches_maintenance.id')
                    ->leftJoin('used_pieces', 'used_pieces.fichepanne_id', '=', 'fichepanne.id')
                    ->where('fichepanne.date_resoudre', '>=', $firstDay)
                    ->where('fichepanne.date_resoudre', '<=', $lastDay)
                    ->whereNull('used_pieces.deleted_at')
                    ->where('used_pieces.piece_id', '=', $piece)
                    ->selectRaw("
                        DATE_FORMAT(fichepanne.date_resoudre, '%Y-%m') as month,
                        COALESCE(SUM(used_pieces.quantité), 0) as total
                    ")
                    ->groupBy('month')
                    ->orderBy('month', 'asc');
                $data = $query->get();
                $query2 = traveauxlibre_model::query()
                    ->leftJoin('traveauxlibreusedpieces', 'traveauxlibreusedpieces.traveauxlibre_id', '=', 'traveauxlibre.id')
                    ->where('traveauxlibre.date_resoudre', '>=', $firstDay)
                    ->where('traveauxlibre.date_resoudre', '<=', $lastDay)
                    ->where('traveauxlibreusedpieces.piece_id', '=', $piece)
                    ->selectRaw("
                DATE_FORMAT(traveauxlibre.date_resoudre, '%Y-%m') as month,
                COALESCE(SUM(traveauxlibreusedpieces.quantité), 0) as total
                ")
                    ->groupBy('month')
                    ->orderBy('month', 'asc');
                $data2 = $query2->get();
                $mergedData = collect();

                foreach ($data as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                foreach ($data2 as $item) {
                    $id = $item->id_bus;
                    if ($mergedData->has($id)) {
                        $mergedData[$id]->total_gasoile += $item->total_gasoile;
                    } else {
                        $mergedData[$id] = $item;
                    }
                }
                $data = $mergedData->values();
            }
        } elseif ($request->data_type == 'bar_agents_mois') {
            $month = $request->month;
            $year = $request->year;
            $piece = $request->piece;
            $firstDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
            $lastDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');
            $agents = maintenance_agent::all();
            $pannesEquipes = fichepanne_model::query()
                ->whereBetween('date_resoudre', [$firstDay, $lastDay])
                ->whereNotNull('equipe')
                ->get(['equipe']);
            $travauxEquipes = traveauxlibre_model::query()
                ->whereBetween('date_resoudre', [$firstDay, $lastDay])
                ->whereNotNull('equipe')
                ->get(['equipe']);
            $panneNames = [];
            foreach ($pannesEquipes as $json) {
                $decoded = json_decode($json, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $name) {
                        $panneNames[] = strtolower(trim($name));
                    }
                }
            }
            $travauxNames = [];
            foreach ($travauxEquipes as $json) {
                $decoded = json_decode($json, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $name) {
                        $travauxNames[] = strtolower(trim($name));
                    }
                }
            }
            // dd($panneNames);
            $data = [];
            foreach ($agents as $agent) {
                $fullname = strtolower(trim($agent->firstname . ' ' . $agent->lastname));
            
                $panneCount = collect($panneNames)->filter(function ($name) use ($fullname) {
                    return str_contains($name, $fullname) || str_contains($fullname, $name);
                })->count();
            
                $travauxCount = collect($travauxNames)->filter(function ($name) use ($fullname) {
                    return str_contains($name, $fullname) || str_contains($fullname, $name);
                })->count();
            
                $data[] = [
                    'agent' => $agent->firstname . ' ' . $agent->lastname,
                    // 'pannes' => $panneCount,
                    // 'travaux' => $travauxCount,
                    'total' => $panneCount + $travauxCount,
                ];
            }
            // usort($data, fn($a, $b) => $b['total'] <=> $a['total']);
        } elseif ($request->data_type == 'ligne_equipe_mois') {
            $year = $request->year;
            $firstDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->startOfYear();
            $lastDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->endOfYear();
            $equipeInput = json_decode($request->piece);
            sort($equipeInput);
            $equipeKey = json_encode($equipeInput);
            $pannes = fichepanne_model::query()
                ->whereBetween('date_resoudre', [$firstDay->format('Y-m-d'), $lastDay->format('Y-m-d')])
                ->whereNotNull('equipe')
                ->get(['date_resoudre', 'equipe']);

            $traveaux = traveauxlibre_model::query()
                ->whereBetween('date_resoudre', [$firstDay->format('Y-m-d'), $lastDay->format('Y-m-d')])
                ->whereNotNull('equipe')
                ->get(['date_resoudre', 'equipe']);
            $counts = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthFormatted = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                $counts[$monthFormatted] = 0;
            }
            foreach ($pannes as $panne) {
                $tmpeqp = json_decode($panne->equipe);
                if (is_array($tmpeqp)) {
                    sort($tmpeqp);
                    if (json_encode($tmpeqp) === $equipeKey) {
                        $month = \Carbon\Carbon::parse($panne->date_resoudre)->format('Y-m');
                        if (isset($counts[$month])) {
                            $counts[$month]++;
                        }
                    }
                }
            }
            foreach ($traveaux as $travaux) {
                $tmpeqp = json_decode($travaux->equipe);
                if (is_array($tmpeqp)) {
                    sort($tmpeqp);
                    if (json_encode($tmpeqp) === $equipeKey) {
                        $month = \Carbon\Carbon::parse($travaux->date_resoudre)->format('Y-m');
                        if (isset($counts[$month])) {
                            $counts[$month]++;
                        }
                    }
                }
            }
            $data = [];
            foreach ($counts as $month => $total) {
                $data[] = [
                    'month' => $month,
                    'total' => $total,
                ];
            }
        }
        return response()->json($data);
    }

    public function generate_suivijournaliere_pdf(Request $request)
    {
        $request->validate([]);

        if ($request->month && $year = $request->year) {
            $month = $request->month;
            $year = $request->year;
            $firstDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
            $lastDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');
            $months_fr_array = [
                1 => 'Janvier',
                2 => 'Février',
                3 => 'Mars',
                4 => 'Avril',
                5 => 'Mai',
                6 => 'Juin',
                7 => 'Juillet',
                8 => 'Août',
                9 => 'Septembre',
                10 => 'Octobre',
                11 => 'Novembre',
                12 => 'Décembre'
            ];
            $monthName = $months_fr_array[$month] . $year;
        } else {
            $firstDay = $request->datedu;
            $lastDay = $request->dateau;
            $monthName = 'Du ' . $firstDay . ' Au ' . $lastDay;
        }



        $buses = Bus::with([
            'maintenanceRecords.fichepanne' => function ($query) use ($firstDay, $lastDay) {
                $query->whereBetween('date_resoudre', [$firstDay, $lastDay]);
            },
            'traveauxlibre' => function ($query) use ($firstDay, $lastDay) {
                $query->whereBetween('date_resoudre', [$firstDay, $lastDay]);
            }
        ])->get();

        $pannes = [];
        $traveaux = [];
        $total = [];

        foreach ($buses as $bus) {
            $fiches = $bus->maintenanceRecords->pluck('fichepanne')->flatten()->sortBy('date_resoudre');
            if ($fiches->isNotEmpty()) {
                $pannes[] = $fiches;
            }

            $trav = $bus->traveauxlibre->sortBy('date_resoudre');
            if ($trav->isNotEmpty()) {
                $traveaux[] = $trav;
            }

            $total = array_merge($total, $fiches->map(function ($panne) use ($bus) {
                return [
                    'name' => $panne->pannename->name,
                    'bus' => $bus->name,
                    'date' => $panne->date_resoudre,
                    'description' => $panne->description,
                    'used_pieces' => $panne->used_pieces,
                    'type' => $panne->pannename->type,
                    'equipe' => $panne->equipe,
                    'brigade' => $panne->brigade,
                    'lieu' => $panne->lieu_resoudre,
                    'item' => 'Panne',
                ];
            })->toArray());

            $total = array_merge($total, $trav->map(function ($panne) use ($bus) {
                return [
                    'name' => $panne->name,
                    'bus' => $bus->name,
                    'date' => $panne->date_resoudre,
                    'description' => $panne->description,
                    'used_pieces' => $panne->used_pieces,
                    'type' => $panne->nature,
                    'equipe' => $panne->equipe,
                    'brigade' => $panne->brigade,
                    'lieu' => $panne->lieu_resoudre,
                    'item' => 'T E',
                ];
            })->toArray());
        }

        $total = collect($total)->sortBy('date');
        $groupedtotal = $total->groupBy(fn($panne) => \Carbon\Carbon::parse($panne['date'])->toDateString());

        // dd($pannes, $traveaux, $total);

        // dd($groupedpannes);
        $html = view('maintenance.etatsuivijournaliere_pdf', compact('groupedtotal', 'monthName'))->render();

        $mpdf = new Mpdf([
            'format' => 'A4',
            // 'tempDir' => sys_get_temp_dir(),
        ]);
        $imagePath = public_path('/LOGO ETUS.png');
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 20, 15, 22, 22, 'png');
        $mpdf->SetY(10);
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: right; font-size: 12px;'>
            Généré le: $currentdate | Page {PAGENO} sur {nbpg}
        </div>
        ";
        $nomfichier = 'Fiche suivi Journalière- ' . $monthName  . '.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        ini_set('pcre.backtrack_limit', 10000000);
        ini_set('pcre.recursion_limit', 10000000);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function generate_fiche_suivie_vidange_pdf(Request $request)
    {
        $request->validate([]);

        if ($request->month && $year = $request->year) {
            $month = $request->month;
            $year = $request->year;
            $firstDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
            $lastDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');
            $months_fr_array = [
                1 => 'Janvier',
                2 => 'Février',
                3 => 'Mars',
                4 => 'Avril',
                5 => 'Mai',
                6 => 'Juin',
                7 => 'Juillet',
                8 => 'Août',
                9 => 'Septembre',
                10 => 'Octobre',
                11 => 'Novembre',
                12 => 'Décembre'
            ];
            $monthName = $months_fr_array[$month] . $year;
        } else {
            $firstDay = $request->datedu;
            $lastDay = $request->dateau;
            $monthName = 'Du ' . $firstDay . ' Au ' . $lastDay;
        }



        $buses = Bus::with([
            'maintenanceRecords.fichepanne' => function ($query) use ($firstDay, $lastDay) {
                $query->whereBetween('date_resoudre', [$firstDay, $lastDay])->whereIn('pannnename_id', [23, 24, 25]);
            }
        ])->get();

        $pannes = [];
        $traveaux = [];
        $total = [];

        foreach ($buses as $bus) {
            $fiches = $bus->maintenanceRecords->pluck('fichepanne')->flatten()->sortBy('date_resoudre');
            if ($fiches->isNotEmpty()) {
                $pannes[] = $fiches;
            }


            $total = array_merge($total, $fiches->map(function ($panne) use ($bus) {
                return [
                    'name' => $panne->pannename->name,
                    'bus' => $bus->name,
                    'date' => $panne->date_resoudre,
                    'description' => $panne->description,
                    'used_pieces' => $panne->used_pieces,
                    'type' => $panne->pannename->type,
                    'equipe' => $panne->equipe,
                    'brigade' => $panne->brigade,
                    'lieu' => $panne->lieu_resoudre,
                    'item' => 'Panne',
                ];
            })->toArray());
        }

        $total = collect($total)->sortBy('date');
        $groupedtotal = $total->groupBy(fn($panne) => \Carbon\Carbon::parse($panne['date'])->toDateString());

        // dd($pannes, $traveaux, $total);

        // dd($groupedpannes);
        $html = view('maintenance.etatsuivievidange_pdf', compact('groupedtotal', 'monthName'))->render();

        $mpdf = new Mpdf([
            'format' => 'A4',
            // 'tempDir' => sys_get_temp_dir(),
        ]);
        $imagePath = public_path('/LOGO ETUS.png');
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 20, 15, 22, 22, 'png');
        $mpdf->SetY(10);
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: right; font-size: 12px;'>
            Généré le: $currentdate | Page {PAGENO} sur {nbpg}
        </div>
        ";
        $nomfichier = 'Fiche suivi Journalière- ' . $monthName  . '.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        ini_set('pcre.backtrack_limit', 10000000);
        ini_set('pcre.recursion_limit', 10000000);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function generate_suividatepanne_pdf(Request $request)
    {
        $request->validate([]);

        if ($request->month && $year = $request->year) {
            $month = $request->month;
            $year = $request->year;
            $firstDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
            $lastDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');
            $months_fr_array = [
                1 => 'Janvier',
                2 => 'Février',
                3 => 'Mars',
                4 => 'Avril',
                5 => 'Mai',
                6 => 'Juin',
                7 => 'Juillet',
                8 => 'Août',
                9 => 'Septembre',
                10 => 'Octobre',
                11 => 'Novembre',
                12 => 'Décembre'
            ];
            $monthName = $months_fr_array[$month] . $year;
        } else {
            $firstDay = $request->datedu;
            $lastDay = $request->dateau;
            $monthName = 'Du ' . $firstDay . ' Au ' . $lastDay;
        }



        $buses = Bus::with([
            'maintenanceRecords.fichepanne' => function ($query) use ($firstDay, $lastDay) {
                $query->whereBetween('date_resoudre', [$firstDay, $lastDay]);
            }
        ])->get();

        $pannes = [];
        $total = [];

        foreach ($buses as $bus) {
            $fiches = $bus->maintenanceRecords->pluck('fichepanne')->flatten()->sortBy('date_resoudre');
            if ($fiches->isNotEmpty()) {
                $pannes[] = $fiches;
            }

            $total = array_merge($total, $fiches->map(function ($panne) use ($bus) {
                if ($panne->fichemaintenance->declaré == false) {
                    return null;
                }
                return [
                    'name' => $panne->pannename->name,
                    'bus' => $bus->name,
                    'datedeclaration' => $panne->fichemaintenance->date_fiche,
                    'date' => $panne->date_resoudre,
                    'chauffeur' => $panne->fichemaintenance->chauffeur->fr_name,
                    'type' => $panne->pannename->type,
                    'equipe' => $panne->equipe,
                    'brigade' => $panne->brigade,
                    'description' => $panne->description,
                    'lieu' => $panne->lieu_resoudre,
                    'used_pieces' => $panne->used_pieces,
                    'item' => 'Panne',
                ];
            })->filter()->toArray());
        }
        $total = collect($total)->sortBy('date');
        $groupedtotal = $total->groupBy(fn($panne) => \Carbon\Carbon::parse($panne['date'])->toDateString());

        // dd($pannes, $traveaux, $total);

        // dd($groupedpannes);
        $html = view('maintenance.suividatepanne_pdf', compact('groupedtotal', 'monthName'))->render();

        $mpdf = new Mpdf([
            'format' => 'A4',
            // 'tempDir' => sys_get_temp_dir(),
        ]);
        $imagePath = public_path('/LOGO ETUS.png');
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 20, 15, 22, 22, 'png');
        $mpdf->SetY(10);
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: right; font-size: 12px;'>
            Généré le: $currentdate | Page {PAGENO} sur {nbpg}
        </div>
        ";
        $nomfichier = 'Fiche suivi Journalière- ' . $monthName  . '.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        ini_set('pcre.backtrack_limit', 10000000);
        ini_set('pcre.recursion_limit', 10000000);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function generate_grandtraveaux_pdf(Request $request)
    {
        $request->validate([
            'datedu' => 'required|date',
            'dateau' => 'required|date|after_or_equal:datedupdf',
        ]);

        $firstDay = $request->datedu;
        $lastDay = $request->dateau;

        $monthName = 'Du ' . $firstDay . ' Au ' . $lastDay;



        $buses = Bus::with([
            // 'maintenanceRecords.fichepanne' => function ($query) use ($firstDay, $lastDay) {
            //     $query->whereBetween('date_resoudre', [$firstDay, $lastDay]);
            // },
            'traveauxlibre' => function ($query) use ($firstDay, $lastDay) {
                $query->whereBetween('date_resoudre', [$firstDay, $lastDay]);
                $query->where('grantraveaux', true);
            }
        ])->get();

        $pannes = [];
        $traveaux = [];
        $total = [];

        foreach ($buses as $bus) {
            // $fiches = $bus->maintenanceRecords->pluck('fichepanne')->flatten()->sortBy('date_resoudre');
            // if ($fiches->isNotEmpty()) {
            //     $pannes[] = $fiches;
            // }

            $trav = $bus->traveauxlibre->sortBy('date_resoudre');
            if ($trav->isNotEmpty()) {
                $traveaux[] = $trav;
            }

            // $total = array_merge($total, $fiches->map(function ($panne) use ($bus) {
            //     return [
            //         'name' => $panne->pannename->name,
            //         'bus' => $bus->name,
            //         'date' => $panne->date_resoudre,
            //         'description' => $panne->description,
            //         'used_pieces' => $panne->used_pieces,
            //         'type' => $panne->pannename->type,
            //         'equipe' => $panne->equipe,
            //         'brigade' => $panne->brigade,
            //         'lieu' => $panne->lieu_resoudre,
            //         'item' => 'Panne',
            //     ];
            // })->toArray());

            $total = array_merge($total, $trav->map(function ($panne) use ($bus) {
                return [
                    'name' => $panne->name,
                    'bus' => $bus->name,
                    'date' => $panne->date_resoudre,
                    'description' => $panne->description,
                    'used_pieces' => $panne->used_pieces,
                    'type' => $panne->nature,
                    'equipe' => $panne->equipe,
                    'brigade' => $panne->brigade,
                    'lieu' => $panne->lieu_resoudre,
                    'item' => 'T E',
                ];
            })->toArray());
        }

        $total = collect($total)->sortBy('date');
        $groupedtotal = $total->groupBy(fn($panne) => \Carbon\Carbon::parse($panne['date'])->toDateString());

        // dd($pannes, $traveaux, $total);

        // dd($groupedpannes);
        $html = view('maintenance.grandtraveaux_pdf', compact('groupedtotal', 'monthName'))->render();

        $mpdf = new Mpdf([
            'format' => 'A4',
            // 'tempDir' => sys_get_temp_dir(),
        ]);
        $imagePath = public_path('/LOGO ETUS.png');
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 20, 15, 22, 22, 'png');
        $mpdf->SetY(10);
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: right; font-size: 12px;'>
            Généré le: $currentdate | Page {PAGENO} sur {nbpg}
        </div>
        ";
        $nomfichier = 'Fiche suivi Journalière- ' . $monthName  . '.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        ini_set('pcre.backtrack_limit', 10000000);
        ini_set('pcre.recursion_limit', 10000000);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }

    public function generate_panneencour_pdf(Request $request)
    {
        $request->validate([
            'typepanne' => 'required',
        ]);


        $typepanne = $request->typepanne;

        $fichepannes = fichepanne_model::query()
            ->join('fiches_maintenance', 'fichepanne.fichemaintenance_id', '=', 'fiches_maintenance.id')
            ->where('fichepanne.solved', false)
            ->orderBy('fiches_maintenance.date_fiche')
            ->get();
        if ($typepanne == "tous") {
            $data = $fichepannes;
        } else {
            $data = $fichepannes->filter(function ($fichepanne) use ($typepanne) {
                return $fichepanne->pannename->type == $typepanne;
            });
        }

        // dd($data);
        $html = view('maintenance.pannecour_pdf', compact('data'))->render();

        $mpdf = new Mpdf([
            'format' => 'A4',
            // 'tempDir' => sys_get_temp_dir(),
        ]);
        $imagePath = public_path('/LOGO ETUS.png');
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 20, 15, 22, 22, 'png');
        $mpdf->SetY(10);
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: right; font-size: 12px;'>
            Généré le: $currentdate | Page {PAGENO} sur {nbpg}
        </div>
        ";
        $nomfichier = 'Panne non résolue.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function generate_etat_vidange_pdf(Request $request)
    {
        $request->validate([
            'type' => 'required',
        ]);


        $typevidange = $request->type;

        $buses = Bus::all();


        // dd($data);
        $html = view('maintenance.etatvidange', compact(['typevidange', 'buses']))->render();

        $mpdf = new Mpdf([
            'format' => 'A4',
            // 'tempDir' => sys_get_temp_dir(),
        ]);
        $imagePath = public_path('/LOGO ETUS.png');
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 20, 15, 22, 22, 'png');
        $mpdf->SetY(10);
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: right; font-size: 12px;'>
            Généré le: $currentdate | Page {PAGENO} sur {nbpg}
        </div>
        ";
        $nomfichier = 'Panne non résolue.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }


    // public function generate_suivibus_pdf(Request $request)
    // {
    //     $request->validate([
    //         'buspdf' => 'required|exists:buses,id',
    //         'month' => 'required',
    //         'year' => 'required',
    //     ]);

    //     $month = $request->month;
    //     $year = $request->year;
    //     $firstDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
    //     $lastDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');
    //     $months_fr_array = [
    //         1 => 'Janvier',
    //         2 => 'Février',
    //         3 => 'Mars',
    //         4 => 'Avril',
    //         5 => 'Mai',
    //         6 => 'Juin',
    //         7 => 'Juillet',
    //         8 => 'Août',
    //         9 => 'Septembre',
    //         10 => 'Octobre',
    //         11 => 'Novembre',
    //         12 => 'Décembre'
    //     ];
    //     $monthName = $months_fr_array[$month] . $year;

    //     $bus = Bus::find($request->buspdf);

    //     $fiches = $bus->maintenanceRecords()
    //         ->whereHas('fichepanne', function ($query) use ($firstDay, $lastDay) {
    //             $query->whereBetween('fichepanne.date_resoudre', [$firstDay, $lastDay]);
    //         })
    //         ->with(['fichepanne' => function ($query) use ($firstDay, $lastDay) {
    //             $query->whereBetween('date_resoudre', [$firstDay, $lastDay]);
    //         }])
    //         ->get();
    //     if (count($fiches) > 0) {
    //         $pannes = $fiches->pluck('fichepanne')->flatten()->sortBy('date_resoudre');
    //     } else {
    //         $pannes = [];
    //     }

    //     // dd($pannes);
    //     $html = view('maintenance.etatsuivibus_pdf', compact('pannes', 'bus', 'monthName'))->render();

    //     $mpdf = new Mpdf([
    //         'format' => 'A4',
    //         // 'tempDir' => sys_get_temp_dir(),
    //     ]);
    //     $imagePath = public_path('/LOGO ETUS.png');
    //     $mpdf->AddPage();
    //     $mpdf->Image($imagePath, 20, 15, 22, 22, 'png');
    //     $mpdf->SetY(10);
    //     date_default_timezone_set('Africa/Algiers');
    //     $currentdate = date('H:i:s d-m-Y');
    //     $htmlFooter = "
    //     <div style='text-align: right; font-size: 12px;'>
    //         Généré le: $currentdate | Page {PAGENO} sur {nbpg}
    //     </div>
    //     ";
    //     $nomfichier = 'Fiche suivi ' . $bus->name . ' - ' . $monthName  . '.pdf';

    //     $mpdf->SetHTMLFooter($htmlFooter);
    //     $mpdf->WriteHTML($html);
    //     return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
    //         'Content-Type' => 'application/pdf',
    //         'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
    //     ]);
    // }
    public function generate_suivibus_pdf(Request $request)
    {
        $request->validate([
            'buspdf' => 'required|exists:buses,id',
            'month' => 'required',
            'year' => 'required',
        ]);

        $month = $request->month;
        $year = $request->year;
        $firstDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
        $lastDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');
        $months_fr_array = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];
        $monthName = $months_fr_array[$month] . $year;

        $bus = Bus::with([
            'maintenanceRecords.fichepanne' => function ($query) use ($firstDay, $lastDay) {
                $query->whereBetween('date_resoudre', [$firstDay, $lastDay]);
            },
            'traveauxlibre' => function ($query) use ($firstDay, $lastDay) {
                $query->whereBetween('date_resoudre', [$firstDay, $lastDay]);
            }
        ])->find($request->buspdf);

        if (!$bus) {
            abort(404, 'Bus not found.');
        }


        $fiches = $bus->maintenanceRecords->pluck('fichepanne')->flatten()->sortBy('date_resoudre');

        $traveaux = $bus->traveauxlibre->sortBy('date_resoudre');

        $pannes = $fiches->map(function ($panne) use ($bus) {
            return [
                'name' => $panne->pannename->name,
                'bus' => $bus->name,
                'date' => $panne->date_resoudre,
                'description' => $panne->description,
                'used_pieces' => $panne->used_pieces,
                'type' => $panne->pannename->type,
                'equipe' => $panne->equipe,
                'brigade' => $panne->brigade,
                'lieu' => $panne->lieu_resoudre,
                'item' => 'Panne',
            ];
        });

        $traveauxTransformed = $traveaux->map(function ($panne) use ($bus) {
            return [
                'name' => $panne->name,
                'bus' => $bus->name,
                'date' => $panne->date_resoudre,
                'description' => $panne->description,
                'used_pieces' => $panne->used_pieces,
                'type' => $panne->nature,
                'equipe' => $panne->equipe,
                'brigade' => $panne->brigade,
                'lieu' => $panne->lieu_resoudre,
                'item' => 'T E',
            ];
        });

        $pannes = $pannes->merge($traveauxTransformed)->sortBy('date');

        // dd($total);

        $html = view('maintenance.etatsuivibus_pdf', compact('pannes', 'bus', 'monthName'))->render();

        $mpdf = new Mpdf([
            'format' => 'A4',
            // 'tempDir' => sys_get_temp_dir(),
        ]);
        $imagePath = public_path('/LOGO ETUS.png');
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 20, 15, 22, 22, 'png');
        $mpdf->SetY(10);
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: right; font-size: 12px;'>
            Généré le: $currentdate | Page {PAGENO} sur {nbpg}
        </div>
        ";
        $nomfichier = 'Fiche suivi ' . $bus->name . ' - ' . $monthName  . '.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function generate_Pannerapport_PDF(Request $request)
    {
        $request->validate([
            'fiche_id' => 'required|exists:fichepanne,id',
        ]);
        $fichepanne = fichepanne_model::find($request->fiche_id);
        if ($fichepanne->solved == true) {
            // dd($fichepanne->fichemaintenance->bus);
            $html = view('maintenance.fichepannerapport_pdf', compact('fichepanne'))->render();
        } else {
            dd($fichepanne);
        }
        $mpdf = new Mpdf([
            'format' => 'A4',
            // 'tempDir' => sys_get_temp_dir(),
        ]);
        $imagePath = public_path('/LOGO ETUS.png');
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 30, 15, 22, 22, 'png');
        $mpdf->SetY(10);
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: right; font-size: 12px;'>
            Généré le: $currentdate | Page {PAGENO} sur {nbpg}
        </div>
        ";
        $nomfichier = 'Rapport de panne' . $fichepanne->fichemaintenance->bus->name   . '.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }



    public function generate_gasoile_PDF(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required',
        ]);
        $month = $request->month;
        $year = $request->year;
        $firstDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
        $lastDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');
        $months_fr_array = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];
        $monthName = $months_fr_array[$month] . $year;

        //
        $query = bus::query()
            ->whereIn('type', ['v8', 'l5'])
            ->leftJoin('fiches_maintenance', function ($join) use ($firstDay, $lastDay) {
                $join->on('buses.id', '=', 'fiches_maintenance.id_bus')
                    ->where('fiches_maintenance.date_fiche', '>=', $firstDay)
                    ->where('fiches_maintenance.date_fiche', '<=', $lastDay)
                    ->where('fiches_maintenance.declaré', '=', true)
                    ->where(function ($q) {
                        $q->where('fiches_maintenance.brigade', 'soir')
                            ->orWhere('fiches_maintenance.brigade', 'matin');
                    });
            })
            ->selectRaw('
            buses.id as id_bus, 
            buses.name as bus_name, 
            COALESCE(SUM(fiches_maintenance.gasoile), 0) as total_gasoile, 
            COALESCE(SUM(fiches_maintenance.kmgobale), 0) as total_km
        ')
            ->groupBy('buses.id', 'buses.name')
            ->orderBy('buses.id');


        $data = $query->get();

        $datedupdf =  \Carbon\Carbon::parse($request->datedupdf)->format('d-m-Y');
        $dateaupdf =  \Carbon\Carbon::parse($request->dateaupdf)->format('d-m-Y');

        $mpdf = new Mpdf([
            'format' => 'A4',

        ]);
        $html = view('maintenance.gasoile_pdf_fr', compact('data', 'monthName'))->render();
        $imagePath = public_path('/LOGO ETUS.png');
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 30, 10, 20, 20, 'png');
        $mpdf->SetY(10);
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
                        <div style='text-align: right; font-size: 12px;'>
                            Généré le: $currentdate | Page {PAGENO} sur {nbpg}
                        </div>
                        ";
        $nomfichier = 'etat_gasoile_' . $monthName  . '.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function generate_km100_PDF(Request $request)
    {
        $request->validate([
            'datedupdf' => 'required|date',
            'dateaupdf' => 'required|date|after_or_equal:datedupdf',
        ]);


        //         $datedupdf = Carbon::parse($request->datedupdf)->startOfDay();
        // $dateaupdf = Carbon::parse($request->dateaupdf)->endOfDay(); // Définit l'heure à 23:59:59

        $query = bus::query()
            ->whereIn('type', ['v8', 'l5'])
            ->leftJoin('fiches_maintenance', function ($join) use ($request) {
                $join->on('buses.id', '=', 'fiches_maintenance.id_bus')
                    ->where('fiches_maintenance.date_fiche', '>=', Carbon::parse($request->datedupdf)->startOfDay())
                    ->where('fiches_maintenance.date_fiche', '<=', Carbon::parse($request->dateaupdf)->endOfDay())
                    ->where('fiches_maintenance.declaré', '=', true)
                    ->where(function ($q) {
                        $q->where('fiches_maintenance.brigade', 'soir')
                            ->orWhere('fiches_maintenance.brigade', 'matin');
                    });
            })
            ->selectRaw('
            buses.id as id_bus, 
            buses.name as bus_name, 
            COALESCE(SUM(fiches_maintenance.gasoile), 0) as total_gasoile, 
            COALESCE(SUM(fiches_maintenance.kmgobale), 0) as total_km
        ')
            ->groupBy('buses.id', 'buses.name')
            ->orderBy('buses.id');


        $data = $query->get();

        $datedupdf =  \Carbon\Carbon::parse($request->datedupdf)->format('d-m-Y');
        $dateaupdf =  \Carbon\Carbon::parse($request->dateaupdf)->format('d-m-Y');

        $mpdf = new Mpdf([
            'format' => 'A4',

        ]);
        if ($request->languepdf == 'fr') {

            $html = view('maintenance.km100_pdf_fr', compact('data', 'datedupdf', 'dateaupdf'))->render();
            $imagePath = public_path('/LOGO ETUS.png');
            $mpdf->AddPage();
            $mpdf->Image($imagePath, 30, 10, 20, 20, 'png');
            $mpdf->SetY(10);
            date_default_timezone_set('Africa/Algiers');
            $currentdate = date('H:i:s d-m-Y');
            $htmlFooter = "
                        <div style='text-align: right; font-size: 12px;'>
                            Généré le: $currentdate | Page {PAGENO} sur {nbpg}
                        </div>
                        ";
            $nomfichier = 'KMau100_parbus_du_' . $datedupdf . '_au_' . $dateaupdf  . '.pdf';
        } else {

            $html = view('maintenance.pdf_ar', compact('data', 'datedupdf', 'dateaupdf'))->render();
            $imagePath = public_path('/LOGO ETUS.png');
            $mpdf->AddPage();
            $mpdf->Image($imagePath, 230, 12, 25, 25, 'png');
            $mpdf->SetY(10);
            date_default_timezone_set('Africa/Algiers');
            $currentdate = date('H:i:s d-m-Y');
            $htmlFooter = "
                        <div style='text-align: left; font-size: 12px;' >
                         صفحة {PAGENO} من {nbpg}  <span>  ثم إستخراج الملف في $currentdate </span>
                        </div>
                        ";
            $nomfichier = 'لائحة الصيانة من_' . $datedupdf . 'إلى' . $dateaupdf  . '.pdf';
        }
        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function generate_etat_nreparatiopdf(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required',
            'languepdf' => 'required',
        ]);

        $month = $request->input('month');
        $year = $request->input('year');
        $brigade = $request->input('brigadeexceleta'); // Ensure the key is correct

        $firstDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
        $lastDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');
        // Query to get all buses and count panne types

        $data = bus::with(['maintenanceRecords.fichepanne.pannename'])
            ->whereIn('type', ['v8', 'l5'])
            // ->whereHas('maintenanceRecords.fichepanne.pannename', function ($query) use ($firstDay, $lastDay) {
            //     $query->whereBetween('created_at', [$firstDay, $lastDay]);
            // })
            ->get()
            ->map(function ($bus) use ($firstDay, $lastDay) {
                $toleCount = 0;
                $mecaniqueCount = 0;
                $electriqueCount = 0;
                $vidangeCount = 0;

                foreach ($bus->maintenanceRecords as $maintenanceRecords) {
                    foreach ($maintenanceRecords->fichepanne as $fichepanne) {
                        if ($fichepanne->date_resoudre >= $firstDay && $fichepanne->date_resoudre <= $lastDay) {
                            // echo($fichepanne->pannename->type);
                            switch ($fichepanne->pannename->type) {
                                case 'tolle':
                                    $toleCount++;
                                    break;
                                case 'jauge':
                                    $mecaniqueCount++;
                                    break;
                                case 'mecanique':
                                    $mecaniqueCount++;
                                    break;
                                case 'electrique':
                                    $electriqueCount++;
                                    break;
                                case 'vidange':
                                    $vidangeCount++;
                                    break;
                            }
                        }
                    }
                }
                foreach ($bus->traveauxlibre as $traveaux) {
                    if ($traveaux->date_resoudre >= $firstDay && $traveaux->date_resoudre <= $lastDay) {
                        switch ($traveaux->nature) {
                            case 'tolle':
                                $toleCount++;
                                break;
                            case 'jauge':
                                $mecaniqueCount++;
                                break;
                            case 'mecanique':
                                $mecaniqueCount++;
                                break;
                            case 'electrique':
                                $electriqueCount++;
                                break;
                            case 'vidange':
                                $vidangeCount++;
                                break;
                        }
                    }
                }

                return [
                    'bus' => $bus->name,
                    'tole' => $toleCount,
                    'mecanique' => $mecaniqueCount,
                    'electrique' => $electriqueCount,
                    'vidange' => $vidangeCount,
                ];
            });

        $mpdf = new Mpdf([
            'format' => 'A4',
        ]);

        if ($request->languepdf == 'fr') {
            $months_fr_array = [
                1 => 'Janvier',
                2 => 'Février',
                3 => 'Mars',
                4 => 'Avril',
                5 => 'Mai',
                6 => 'Juin',
                7 => 'Juillet',
                8 => 'Août',
                9 => 'Septembre',
                10 => 'Octobre',
                11 => 'Novembre',
                12 => 'Décembre'
            ];
            $monthName = $months_fr_array[$month];
            $html = view('maintenance.etatnreparatiopdf', compact('data', 'year', 'monthName'))->render();
            $imagePath = public_path('/LOGO ETUS.png');
            $mpdf->AddPage();
            $mpdf->Image($imagePath, 30, 10, 20, 20, 'png');
            $mpdf->SetY(10);
            date_default_timezone_set('Africa/Algiers');
            $currentdate = date('H:i:s d-m-Y');
            $htmlFooter = "
                        <div style='text-align: right; font-size: 12px;'>
                            Généré le: $currentdate | Page {PAGENO} sur {nbpg}
                        </div>
                        ";
            $nomfichier = 'Panne_reparer_d_' . $monthName . '_' . $year  . '.pdf';
        } else {

            $html = view('maintenance.etatnreparatiopdf', compact('data', 'year', 'monthName'))->render();
            $imagePath = public_path('/LOGO ETUS.png');
            $mpdf->AddPage();
            $mpdf->Image($imagePath, 230, 12, 25, 25, 'png');
            $mpdf->SetY(10);
            date_default_timezone_set('Africa/Algiers');
            $currentdate = date('H:i:s d-m-Y');
            $htmlFooter = "
                        <div style='text-align: left; font-size: 12px;' >
                         صفحة {PAGENO} من {nbpg}  <span>  ثم إستخراج الملف في $currentdate </span>
                        </div>
                        ";
            $nomfichier = 'لائحة الصيانة من_.pdf';
        }
        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function generatePDF(Request $request)
    {
        $request->validate([
            'datedupdf' => 'required|date',
            'dateaupdf' => 'required|date|after_or_equal:datedupdf',
            'brigadepdf' => 'required|string',
        ]);
        $query = fichemaintenance::query();

        if ($request->datedupdf) {
            $query->where('date_fiche', '>=', $request->datedupdf);
        }

        if ($request->dateaupdf) {
            $query->where('date_fiche', '<=', $request->dateaupdf);
        }

        if ($request->brigadepdf) {
            if ($request->brigadepdf == 'jour') {
                $query->whereIn('brigade', ['soir', 'matin']);
            } else {
                $query->where('brigade', $request->brigadepdf);
            }
        }

        $query->with(['bus', 'ligne'])->orderBy('date_fiche')->orderBy('id_bus');
        $data = $query->get();

        $datedupdf =  \Carbon\Carbon::parse($request->datedupdf)->format('d-m-Y');
        $dateaupdf =  \Carbon\Carbon::parse($request->dateaupdf)->format('d-m-Y');
        $brigadepdf = $request->brigadepdf;

        $mpdf = new Mpdf([
            'format' => 'A4-L',
            // 'tempDir' => sys_get_temp_dir(),
        ]);
        if ($request->languepdf == 'fr') {
            if ($brigadepdf == 'jour') {
                $brigadepdf = "Matin et Soir";
            } else {
                if ($brigadepdf == "matin") {
                    $brigadepdf = "Matin";
                } else {
                    $brigadepdf = "Soir";
                }
            }
            $html = view('maintenance.pdf_fr', compact('data', 'datedupdf', 'dateaupdf', 'brigadepdf'))->render();
            $imagePath = public_path('/LOGO ETUS.png');
            $mpdf->AddPage();
            $mpdf->Image($imagePath, 30, 12, 25, 25, 'png');
            $mpdf->SetY(10);
            date_default_timezone_set('Africa/Algiers');
            $currentdate = date('H:i:s d-m-Y');
            $htmlFooter = "
                        <div style='text-align: right; font-size: 12px;'>
                            Généré le: $currentdate | Page {PAGENO} sur {nbpg}
                        </div>
                        ";
            $nomfichier = 'Liste_maintenance_du_' . $datedupdf . '_au_' . $dateaupdf  . '.pdf';
        } else {
            if ($brigadepdf == 'jour') {
                $brigadepdf = "صباحا ومساء";
            } else {
                if ($brigadepdf == "matin") {
                    $brigadepdf = "فترة صباحية";
                } else {
                    $brigadepdf = "فترة مسائية";
                }
            }
            $html = view('maintenance.pdf_ar', compact('data', 'datedupdf', 'dateaupdf', 'brigadepdf'))->render();
            $imagePath = public_path('/LOGO ETUS.png');
            $mpdf->AddPage();
            $mpdf->Image($imagePath, 230, 12, 25, 25, 'png');
            $mpdf->SetY(10);
            date_default_timezone_set('Africa/Algiers');
            $currentdate = date('H:i:s d-m-Y');
            $htmlFooter = "
                        <div style='text-align: left; font-size: 12px;' >
                         صفحة {PAGENO} من {nbpg}  <span>  ثم إستخراج الملف في $currentdate </span>
                        </div>
                        ";
            $nomfichier = 'لائحة الصيانة من_' . $datedupdf . 'إلى' . $dateaupdf  . '.pdf';
        }
        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function generate_etat_piece_pdf(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required',
            'piece' => 'required',
        ]);

        $month = $request->month;
        $year = $request->year;
        $firstDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
        $lastDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');
        $months_fr_array = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];
        $monthName = $months_fr_array[$month] . ' ' . $request->year;
        $piecename = pieces_maintanance::find($request->piece)->name;
        $pieces = used_pieces::query()
            ->where('piece_id', '=', $request->piece)
            ->whereHas('fichepanne', function ($query) use ($firstDay, $lastDay) {
                $query->whereBetween('date_resoudre', [$firstDay, $lastDay]);
            })
            ->get()
            ->map(function ($piece) {
                return [
                    'id' => $piece->id,
                    'name' => $piece->piece->name,
                    'quantite' => $piece->quantité,
                    'date' => $piece->fichepanne->date_resoudre,
                    'bus' => $piece->fichepanne->fichemaintenance->bus->name,
                    'equipe' => $piece->fichepanne->equipe,
                    'panne' => $piece->fichepanne->pannename->name
                ];
            });
        $tlpieces = traveauxlibreusedpieces::query()
            ->where('piece_id', '=', $request->piece)
            ->whereHas('traveauxlibre', function ($query) use ($firstDay, $lastDay) {
                $query->whereBetween('date_resoudre', [$firstDay, $lastDay]);
            })
            ->get()
            ->map(function ($piece) {
                return [
                    'id' => $piece->id,
                    'name' => $piece->piece->name,
                    'quantite' => $piece->quantité,
                    'date' => $piece->traveauxlibre->date_resoudre,
                    'bus' => $piece->traveauxlibre->bus->name,
                    'equipe' => $piece->traveauxlibre->equipe,
                    'panne' => $piece->traveauxlibre->name
                ];
            });

        // dd($firstDay, $pieces, $tlpieces);
        $mergedPieces = collect($pieces)->merge($tlpieces);
        $groupedtotal = $mergedPieces->groupBy('bus');
        // dd($groupedtotal);

        $mpdf = new Mpdf([
            'format' => 'A4',

        ]);
        $imagePath = public_path('/LOGO ETUS.png');
        $html = view('maintenance.etatpiecepdf', compact(['groupedtotal', 'monthName', 'piecename']))->render();
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 30, 10, 20, 20, 'png');
        $mpdf->SetY(10);
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
                        <div style='text-align: right; font-size: 12px;'>
                            Généré le: $currentdate | Page {PAGENO} sur {nbpg}
                        </div>
                        ";
        $nomfichier = 'etat_' . $piecename . '_' . $monthName  . '.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function generate_etat_piece_sansvidange_pdf(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required',
            'piece' => 'required',
        ]);

        $month = $request->month;
        $year = $request->year;
        $firstDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
        $lastDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');
        $months_fr_array = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];
        $monthName = $months_fr_array[$month] . ' ' . $request->year;
        $piecename = pieces_maintanance::find($request->piece)->name;
        $pieces = used_pieces::query()
            ->where('piece_id', '=', $request->piece)
            ->whereHas('fichepanne', function ($query) use ($firstDay, $lastDay) {
                $query->whereBetween('date_resoudre', [$firstDay, $lastDay])
                    ->whereNotIn('pannnename_id', [23, 24, 25]);
            })
            ->get()
            ->map(function ($piece) {
                return [
                    'id' => $piece->id,
                    'name' => $piece->piece->name,
                    'quantite' => $piece->quantité,
                    'date' => $piece->fichepanne->date_resoudre,
                    'bus' => $piece->fichepanne->fichemaintenance->bus->name,
                    'equipe' => $piece->fichepanne->equipe,
                    'panne' => $piece->fichepanne->pannename->name
                ];
            });
        $tlpieces = traveauxlibreusedpieces::query()
            ->where('piece_id', '=', $request->piece)
            ->whereHas('traveauxlibre', function ($query) use ($firstDay, $lastDay) {
                $query->whereBetween('date_resoudre', [$firstDay, $lastDay]);
            })
            ->get()
            ->map(function ($piece) {
                return [
                    'id' => $piece->id,
                    'name' => $piece->piece->name,
                    'quantite' => $piece->quantité,
                    'date' => $piece->traveauxlibre->date_resoudre,
                    'bus' => $piece->traveauxlibre->bus->name,
                    'equipe' => $piece->traveauxlibre->equipe,
                    'panne' => $piece->traveauxlibre->name
                ];
            });

        // dd($firstDay, $pieces, $tlpieces);
        $mergedPieces = collect($pieces)->merge($tlpieces);
        $groupedtotal = $mergedPieces->groupBy('bus');
        // dd($groupedtotal);

        $mpdf = new Mpdf([
            'format' => 'A4',

        ]);
        $imagePath = public_path('/LOGO ETUS.png');
        $html = view('maintenance.etatpiecepdf', compact(['groupedtotal', 'monthName', 'piecename']))->render();
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 30, 10, 20, 20, 'png');
        $mpdf->SetY(10);
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
                        <div style='text-align: right; font-size: 12px;'>
                            Généré le: $currentdate | Page {PAGENO} sur {nbpg}
                        </div>
                        ";
        $nomfichier = 'etat_' . $piecename . '_' . $monthName  . '.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function generateEXCEL(Request $request)
    {
        $request->validate([
            'dateduexcel' => 'required|date',
            'dateauexcel' => 'required|date|after_or_equal:dateduexcel',
            'brigadeexcel' => 'required|string',
        ]);
        // dd($request);
        $query = fichemaintenance::query();
        $query->where('declaré', '=', true);

        if ($request->dateduexcel) {
            $query->where('date_fiche', '>=', $request->dateduexcel);
        }

        if ($request->dateauexcel) {
            $query->where('date_fiche', '<=', $request->dateauexcel);
        }

        if ($request->brigadeexcel) {
            if ($request->brigadeexcel == 'jour') {
                $query->whereIn('brigade', ['soir', 'matin']);
            } else {
                $query->where('brigade', $request->brigadeexcel);
            }
        }

        $query->with(['bus', 'ligne'])->orderBy('date_fiche')->orderBy('id_bus');

        $dateduexcel =  \Carbon\Carbon::parse($request->dateduexcel)->format('d-m-Y');
        $dateauexcel =  \Carbon\Carbon::parse($request->dateauexcel)->format('d-m-Y');
        $brigadeexcel = $request->brigadeexcel;
        if ($brigadeexcel == 'jour') {
            $brigadeexcel = "Matin et Soir";
        } else {
            if ($brigadeexcel == "matin") {
                $brigadeexcel = "Matin";
            } else {
                $brigadeexcel = "Soir";
            }
        }
        $data = $query->get();
        // dd($data);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Date de début:');
        $sheet->setCellValue('B1', $request->dateduexcel);
        $sheet->setCellValue('A2', 'Date de fin:');
        $sheet->setCellValue('B2', $request->dateauexcel);
        $sheet->setCellValue('A3', 'Brigade:');
        $sheet->setCellValue('B3', $request->brigadeexcel);

        $sheet->setCellValue('A5', 'N°');
        $sheet->setCellValue('B5', 'Date');
        $sheet->setCellValue('C5', 'ID Bus');
        $sheet->setCellValue('D5', 'Brigade');
        $sheet->setCellValue('E5', 'Ligne');
        $sheet->setCellValue('F5', 'H.Depart');
        $sheet->setCellValue('G5', 'H.Arrivée');
        $sheet->setCellValue('H5', 'gasoile');
        $sheet->setCellValue('I5', 'KM.depart');
        $sheet->setCellValue('J5', 'KM.arrivée');
        $sheet->setCellValue('K5', 'KM.globale');
        $sheet->setCellValue('L5', 'KM.HLP');
        $sheet->setCellValue('M5', 'KM.Comm');

        $row = 6;
        $i = 1;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $item->date_fiche);
            $sheet->setCellValue('C' . $row, $item->bus->name);
            $sheet->setCellValue('D' . $row, $item->brigade);
            $sheet->setCellValue('E' . $row, $item->ligne->name ?? '/');
            $sheet->setCellValue('F' . $row, $item->heur_depart);
            $sheet->setCellValue('G' . $row, $item->heur_arrive);
            $sheet->setCellValue('H' . $row, $item->gasoile);
            $sheet->setCellValue('I' . $row, $item->kmdepart);
            $sheet->setCellValue('J' . $row, $item->kmarrive);
            $sheet->setCellValue('K' . $row, $item->kmgobale);
            $sheet->setCellValue('L' . $row, $item->kmhlp);
            $sheet->setCellValue('M' . $row, $item->kmcommerciale);
            $row++;
            $i++;
        }
        foreach (range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $fileName = 'Liste_maintenance_du_' . $request->dateduexcel . '_au_' . $request->dateauexcel . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    public function generateETATKilometrage(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required',
            'brigadeexceleta' => 'required',
        ]);
        $month = $request->input('month');
        $year = $request->input('year');
        $brigade = $request->brigadeexceleta;
        $firstDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
        $lastDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');
        $query = fichemaintenance::query();
        $query->where('declaré', '=', true);
        $query->where('date_fiche', '>=', $firstDay);
        $query->where('date_fiche', '<=', $lastDay);
        if ($brigade) {
            if ($brigade == 'jour') {
                $query->whereIn('brigade', ['matin', 'soir']);
            } else {
                $query->where('brigade', $brigade);
            }
        }
        // $data = $query->orderBy('date_fiche')->orderBy('id_bus')->get();
        $data = $query->orderBy('id_bus')->orderBy('date_fiche')->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        if ($data->isEmpty()) {
            $writer = new Xlsx($spreadsheet);
            $fileName = "Bus_Names_{$year}_{$month}.xlsx";

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        }
        $months_fr_array = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];
        $monthName = $months_fr_array[$month];
        $sheet->setCellValue('A1', 'Kilométrage ' . $monthName . ' ' . $year . ' ' . $brigade);
        $sheet->getStyle('A1')->getFont()->setSize(72);
        $sheet->mergeCells('A1:W1');
        $row = 3;
        $beganrow = $row;
        $col = 'A';
        $busNames = $data->pluck('bus.name')->unique();
        $days = $data->pluck('date_fiche')->unique();
        $colorA = 'FFDDFCBA';
        $colorB = 'FF95F7EF';
        $color = $colorA;
        foreach ($busNames as $busname) {
            if ($color === $colorB) {
                $color = $colorA;
            } else {
                $color = $colorB;
            }
            $sheet->setCellValue($col . $row, 'Jour');
            $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $col++;
            $sheet->setCellValue($col . $row - 1, $busname);
            $sheet->setCellValue($col . $row, "Compteur");
            $sheet->getStyle($col . $row - 1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . $row - 1)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
            $sheet->getStyle($col . $row - 1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle($col . $row - 1)->getFont()->setBold(true);
            $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle($col . $row)->getFont()->setBold(true);
            $col++;
            $sheet->setCellValue($col . $row, "K/Jour");
            $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $col++;
        }
        $sheet->setCellValue($col . $row, 'Total');
        $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        foreach ($days as $daye) {
            $dist_cells = "";
            $col = 'A';
            $row++;
            foreach ($busNames as $busname) {
                if ($color === $colorB) {
                    $color = $colorA;
                } else {
                    $color = $colorB;
                }
                $dayFormatted = Carbon::parse($daye)->toDateString();
                if ($brigade === 'jour') {
                    $matinRecord = fichemaintenance::whereHas('bus', function ($query) use ($busname) {
                        $query->where('name', $busname);
                    })
                        ->where('declaré', '=', true)
                        ->where('date_fiche', $dayFormatted)
                        ->where('brigade', 'matin')
                        ->first();

                    $soirRecord = fichemaintenance::whereHas('bus', function ($query) use ($busname) {
                        $query->where('name', $busname);
                    })
                        ->where('declaré', '=', true)
                        ->where('date_fiche', $dayFormatted)
                        ->where('brigade', 'soir')
                        ->first();

                    if ($matinRecord && $soirRecord) {
                        if ($soirRecord->kmarrive === 0.0) {
                            // dd($matinRecord,$soirRecord);
                            $kDepartValue = $matinRecord->kmdepart;
                            $kDistValue = $matinRecord->kmarrive - $matinRecord->kmdepart;
                        } elseif ($matinRecord->kmarrive === 0.0) {
                            $kDepartValue = $soirRecord->kmdepart;
                            $kDistValue = $soirRecord->kmarrive - $soirRecord->kmdepart;
                        } else {
                            $kDepartValue = $matinRecord->kmdepart;
                            $kDistValue = $soirRecord->kmarrive - $matinRecord->kmdepart;
                        }
                    } elseif ($matinRecord) {
                        $kDepartValue = $matinRecord->kmdepart;
                        $kDistValue = $matinRecord->kmarrive - $matinRecord->kmdepart;
                    } elseif ($soirRecord) {
                        $kDepartValue = $soirRecord->kmdepart;
                        $kDistValue = $soirRecord->kmarrive - $soirRecord->kmdepart;
                    } else {
                        $kDepartValue = '';
                        $kDistValue = '';
                    }
                } else {
                    $maintenanceRecord = fichemaintenance::whereHas('bus', function ($query) use ($busname) {
                        $query->where('name', $busname);
                    })
                        ->where('date_fiche', $dayFormatted)
                        ->where('brigade', $brigade)
                        ->first();

                    $kDepartValue = $maintenanceRecord ? $maintenanceRecord->kmdepart : '';
                    $kDistValue = $maintenanceRecord ? $maintenanceRecord->kmarrive - $maintenanceRecord->kmdepart : '';
                }
                $sheet->setCellValue($col . $row, Carbon::parse($daye)->day);
                $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
                $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle($col . $row)->getFont()->setBold(true);
                $col++;
                $sheet->setCellValue($col . $row, $kDepartValue);
                $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
                $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $col++;
                $sheet->setCellValue($col . $row, $kDistValue);
                $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
                $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $dist_cells = $dist_cells . 'IF(' . $col . $row . '="",0,' . $col . $row . ')+';
                $col++;
            }
            $sheet->setCellValue($col . $row, '=' . substr($dist_cells, 0, -1) . '');
        }

        $row++;
        $col = 'A';
        foreach ($busNames as $busname) {
            if ($color === $colorB) {
                $color = $colorA;
            } else {
                $color = $colorB;
            }
            // $sheet->setCellValue($col . $row, 'Total');
            $col++;
            $col++;
            $sheet->setCellValue($col . $row, '=SUM(' . $col . $beganrow . ':' . $col . $row - 1 . ')');
            $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $col++;
        }
        $sheet->setCellValue($col . $row, '=SUM(' . $col . $beganrow . ':' . $col . $row - 1 . ')');
        foreach (range('A', $col) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = "Etat_Kilometrage_{$monthName}.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
    public function generate_gasoile_EXCEL(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required',
        ]);
        $month = $request->input('month');
        $year = $request->input('year');
        $firstDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
        $lastDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');
        $months_fr_array = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];

        $monthName = $months_fr_array[$month] . $year;

        $query = fichemaintenance::query();
        $query->where('declaré', '=', true);
        $query->where('date_fiche', '>=', $firstDay);
        $query->where('date_fiche', '<=', $lastDay);
        $query->whereIn('brigade', ['matin', 'soir']);


        $data = $query->orderBy('id_bus')->orderBy('date_fiche')->get();

        $data = $query->get();

        // dd($data);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        if ($data->isEmpty()) {
            $writer = new Xlsx($spreadsheet);
            $fileName = "Bus_Names_{$year}_{$month}.xlsx";

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        }

        $sheet->setCellValue('A1', 'Gasoile ' . $monthName);
        $sheet->getStyle('A1')->getFont()->setSize(72);
        $sheet->mergeCells('A1:W1');
        $row = 3;
        $beganrow = $row;
        $col = 'A';
        $busNames = $data->pluck('bus.name')->unique();
        $days = $data->pluck('date_fiche')->unique();
        $colorA = 'FFDDFCBA';
        $colorB = 'FF95F7EF';
        $color = $colorA;
        foreach ($busNames as $busname) {
            if ($color === $colorB) {
                $color = $colorA;
            } else {
                $color = $colorB;
            }
            $sheet->setCellValue($col . $row, 'Jour');
            $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $col++;
            $sheet->setCellValue($col . $row - 1, $busname);
            $sheet->setCellValue($col . $row, "Gasoile");
            $sheet->getStyle($col . $row - 1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . $row - 1)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
            $sheet->getStyle($col . $row - 1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle($col . $row - 1)->getFont()->setBold(true);
            $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle($col . $row)->getFont()->setBold(true);
            $col++;
            // $sheet->setCellValue($col . $row, "K/Jour");
            // $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            // $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
            // $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            // $col++;
        }
        $sheet->setCellValue($col . $row, 'Total');
        $sheet->getStyle($col . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        foreach ($days as $daye) {
            $dist_cells = "";
            $col = 'A';
            $row++;
            foreach ($busNames as $busname) {
                if ($color === $colorB) {
                    $color = $colorA;
                } else {
                    $color = $colorB;
                }
                $dayFormatted = Carbon::parse($daye)->toDateString();

                $matinRecord = fichemaintenance::whereHas('bus', function ($query) use ($busname) {
                    $query->where('name', $busname);
                })
                    ->where('declaré', '=', true)
                    ->where('date_fiche', $dayFormatted)
                    ->where('brigade', 'matin')
                    ->first();

                $soirRecord = fichemaintenance::whereHas('bus', function ($query) use ($busname) {
                    $query->where('name', $busname);
                })
                    ->where('declaré', '=', true)
                    ->where('date_fiche', $dayFormatted)
                    ->where('brigade', 'soir')
                    ->first();

                if ($matinRecord && $soirRecord) {
                    // dd($matinRecord,$soirRecord);
                    $gasoile = $matinRecord->gasoile + $soirRecord->gasoile;
                } elseif ($matinRecord) {
                    $gasoile = $matinRecord->gasoile;
                } elseif ($soirRecord) {
                    $gasoile = $soirRecord->gasoile;
                } else {
                    $gasoile = '';
                }

                $sheet->setCellValue($col . $row, Carbon::parse($daye)->day);
                $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
                $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle($col . $row)->getFont()->setBold(true);
                $col++;
                $sheet->setCellValue($col . $row, $gasoile);
                $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
                $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                // $col++;
                // $sheet->setCellValue($col . $row, $gasoile);
                // $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
                // $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $dist_cells = $dist_cells . 'IF(' . $col . $row . '="",0,' . $col . $row . ')+';
                $col++;
            }
            $sheet->setCellValue($col . $row, '=' . substr($dist_cells, 0, -1) . '');
        }

        $row++;
        $col = 'A';
        foreach ($busNames as $busname) {
            if ($color === $colorB) {
                $color = $colorA;
            } else {
                $color = $colorB;
            }
            // $sheet->setCellValue($col . $row, 'Total');
            $col++;
            // $col++;
            $sheet->setCellValue($col . $row, '=SUM(' . $col . $beganrow . ':' . $col . $row - 1 . ')');
            $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);
            $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $col++;
        }
        $sheet->setCellValue($col . $row, '=SUM(' . $col . $beganrow . ':' . $col . $row - 1 . ')');
        foreach (range('A', $col) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = "Etat_Gasoile_{$monthName}.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
