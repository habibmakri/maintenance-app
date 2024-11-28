<?php

namespace App\Http\Controllers;

use App\Http\Requests\maintenanceEditRequest;
use App\Http\Requests\maintenanceinRequest;
use App\Models\Bus;
use App\Models\fichemaintenance;
use App\Models\Ligne;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class maintenanceController extends Controller
{
    public function maintenance_in(Request $request)
    {
        $buses = Bus::all();
        $lines = Ligne::all();
        return view("maintenance.maintenancein", compact('buses', 'lines'));
    }

    public function insertFichemaintenance(maintenanceinRequest $request)
    {
        $ficheitem = $request->validated();
        $exists = fichemaintenance::where('id_bus', $ficheitem['bus'])
            ->where('date_fiche', $ficheitem['date'])
            ->where('brigade', $ficheitem['brigade'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Fiche déjà remplie pour ce bus à cette date.');
        }
        if ($ficheitem['partit'] == "oui") {
            fichemaintenance::create([
                'user_id' => Auth::user()->id,
                'date_fiche' => $ficheitem['date'],
                'id_bus' => $ficheitem['bus'],
                'id_ligne' => $ficheitem['ligne'],
                'brigade' => $ficheitem['brigade'],
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
            fichemaintenance::create([
                'user_id' => Auth::user()->id,
                'date_fiche' => $ficheitem['date'],
                'id_bus' => $ficheitem['bus'],
                'id_ligne' => null,
                'brigade' => $ficheitem['brigade'],
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

        return redirect()->back()->with('success', 'Fiche remplie avec succès.');
    }
    public function checkBuses(Request $request)
    {
        $date = $request->query('date');
        $brigade = $request->query('brigade');

        $buses = Bus::with('maintenanceRecords')
            ->get()
            ->map(function ($bus) use ($date, $brigade) {
                $isFilled = $bus->maintenanceRecords
                    ->where('date_fiche', $date)
                    ->where('brigade', $brigade)
                    ->isNotEmpty();

                return [
                    'id' => $bus->id,
                    'name' => $bus->name,
                    'filled' => $isFilled,
                ];
            });
        return response()->json($buses);
    }
    // public function refreshfichtable(Request $request)
    // {
    //     $query = fichemaintenance::query();

    //     if ($request->datedu) {
    //         $query->where('date_fiche', '>=', $request->datedu);
    //     }

    //     if ($request->dateau) {
    //         $query->where('date_fiche', '<=', $request->dateau);
    //     }

    //     if ($request->brigade) {
    //         $query->where('brigade', $request->brigade);
    //     }

    //     $data = $query->with(['bus', 'ligne']) 
    //                  ->get()
    //                  ->map(function ($item) {
    //                      return [
    //                          'bus' => $item->bus->name, 
    //                          'ligne' => $item->ligne->name,
    //                          'city' => $item->ligne->city, 
    //                          'heur_depart' => $item->heur_depart,
    //                          'heur_arrive' => $item->heur_arrive,
    //                          'gasoile' => $item->gasoile,
    //                          'kmgobale' => $item->kmgobale,
    //                          'kmcommerciale' => $item->kmcommerciale,
    //                          'brigade' => $item->brigade,
    //                          'date_fiche' => $item->date_fiche->format('Y-m-d'), 
    //                      ];
    //                  });

    //     return response()->json(['data' => $data]);
    // }
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
            $query->with(['bus', 'ligne'])->orderBy('date_fiche')->orderBy('id_bus');

            $data = $query->get()->map(function ($item) {
                if ($item->ligne) {
                    return [
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
    public function refreshfixtable(Request $request)
    {
        try {
            // 
            $query = fichemaintenance::query();


            if ($request->date) {
                $query->where('date_fiche', '=', $request->date);
            }
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
        return view('maintenance.maintenanceshow');
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
    public function editfiche($id)
    {
        $record = fichemaintenance::find($id);
        $buses = Bus::all();
        $lines = Ligne::all();
        if ($record) {
            return view('maintenance.maintenanceedit', compact('record', 'buses', 'lines'));
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
}
