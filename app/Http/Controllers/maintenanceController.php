<?php

namespace App\Http\Controllers;

use App\Http\Requests\maintenanceEditRequest;
use App\Http\Requests\maintenanceinRequest;
use App\Models\Bus;
use App\Models\fichemaintenance;
use App\Models\fichepanne_model;
use App\Models\Ligne;
use App\Models\Panne;
use App\Models\Station;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class maintenanceController extends Controller
{
    public function maintenance_in(Request $request)
    {
        $buses = Bus::all();
        $lines = Ligne::all();
        $stations = Station::all();
        $pannes = Panne::all();
        return view("maintenance.maintenancein", compact('buses', 'lines', 'stations', 'pannes'));
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
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Fiche déjà remplie pour ce bus à cette date.');
        }

        $ficheData = [
            'user_id' => Auth::user()->id,
            'date_fiche' => $ficheitem['date'],
            'id_bus' => $ficheitem['bus'],
            'brigade' => $ficheitem['brigade'],
            'heur_depart' => $ficheitem['partit'] === 'oui' ? $ficheitem['hdepart'] : '00:00',
            'heur_arrive' => $ficheitem['partit'] === 'oui' ? $ficheitem['harrive'] : '00:00',
            'gasoile' => $ficheitem['partit'] === 'oui' ? $ficheitem['gasoile'] : '0',
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
            ->get()
            ->map(function ($bus) use ($date, $brigade) {
                $isFilled = $bus->maintenanceRecords
                    ->where('date_fiche', $date)
                    ->where('brigade', $brigade)
                    ->isNotEmpty();

                return [
                    'id' => $bus->id,
                    'name' => $bus->name,
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
        $stations = Station::all();
        if ($record) {
            return view('maintenance.maintenanceedit', compact('record', 'buses', 'lines', 'stations'));
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
    public function generateEXCEL(Request $request)
    {
        $request->validate([
            'dateduexcel' => 'required|date',
            'dateauexcel' => 'required|date|after_or_equal:dateduexcel',
            'brigadeexcel' => 'required|string',
        ]);
        // dd($request->dateauexcel);
        $query = fichemaintenance::query();

        if ($request->datedupexcel) {
            $query->where('date_fiche', '>=', $request->datedupexcel);
        }

        if ($request->dateaupexcel) {
            $query->where('date_fiche', '<=', $request->dateaupexcel);
        }

        if ($request->brigadepexcel) {
            if ($request->brigadepexcel == 'jour') {
                $query->whereIn('brigade', ['soir', 'matin']);
            } else {
                $query->where('brigade', $request->brigadepexcel);
            }
        }

        $query->with(['bus', 'ligne'])->orderBy('date_fiche')->orderBy('id_bus');

        $datedupexcel =  \Carbon\Carbon::parse($request->datedupexcel)->format('d-m-Y');
        $dateaupexcel =  \Carbon\Carbon::parse($request->dateaupexcel)->format('d-m-Y');
        $brigadepexcel = $request->brigadepexcel;
        if ($brigadepexcel == 'jour') {
            $brigadepexcel = "Matin et Soir";
        } else {
            if ($brigadepexcel == "matin") {
                $brigadepexcel = "Matin";
            } else {
                $brigadepexcel = "Soir";
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
                        ->where('date_fiche', $dayFormatted)
                        ->where('brigade', 'matin')
                        ->first();

                    $soirRecord = fichemaintenance::whereHas('bus', function ($query) use ($busname) {
                        $query->where('name', $busname);
                    })
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
                            $kDistValue = $soirRecord->kmarrive - $matinRecord->kmdepart;
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
        $fileName = "Bus_Names_{$year}_{$month}.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
