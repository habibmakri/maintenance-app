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
use App\Models\declaration_judiciaire;
use App\Models\Ligne;
use App\Models\Panne;
use App\Models\pieces_maintanance;
use App\Models\Station;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;

class judiciaireController extends Controller
{
    public function judiciaire_in()
    {
        $buses = Bus::all();
        $chauffeurs = chauffeurs::all();
        $lines = Ligne::all();
        return view("judiciaire.declare", compact(['buses', 'chauffeurs', 'lines']));
    }
    public function do_judiciaire_in(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'number' => ['required', 'regex:/^\d{3}$/'],
            'bus' => 'required',
            'chauffeur' => 'required',
            'ligne' => 'required',
            'day' => 'required|date',
            'time' => 'required',
            'place' => 'required|string',
            'description' => 'nullable|string',
            'pertes' => 'nullable|string',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        try {
            $bus = Bus::findOrFail($request->bus);
            $imagePaths = [];

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $imageName = time() . '_' . $request->day . '_' . $bus->name . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('judiciaire_img', $imageName, 'public');
                    $imagePaths[] = 'storage/' . $path;
                }
            }
            // if ($request->hasFile('photos')) {
            //     foreach ($request->file('photos') as $photo) {
            //         $imageName = time() . '_' . $request->day . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            //         $destinationPath = storage_path('app/public/judiciaire_img/' . $imageName);


            //         $imageType = $photo->getClientOriginalExtension();


            //         if ($imageType == 'jpeg' || $imageType == 'jpg') {
            //             $image = imagecreatefromjpeg($photo->getRealPath());
            //         } elseif ($imageType == 'png') {
            //             $image = imagecreatefrompng($photo->getRealPath());
            //         } else {
            //             continue; 
            //         }


            //         list($width, $height) = getimagesize($photo);
            //         $newWidth = 1024;
            //         $newHeight = ($height / $width) * 1024;
            //         $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            //         imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);


            //         if ($imageType == 'jpeg' || $imageType == 'jpg') {
            //             imagejpeg($resizedImage, $destinationPath, 60); 
            //         } elseif ($imageType == 'png') {
            //             imagepng($resizedImage, $destinationPath, 6); 
            //         }


            //         imagedestroy($image);
            //         imagedestroy($resizedImage);

            //         $imagePaths[] = 'storage/judiciaire_img/' . $imageName;
            //     }
            // }



            declaration_judiciaire::create([
                'date_fiche' => $request->date,
                'number' => $request->number,
                'caat' => false,
                'paye' => false,
                'id_bus' => $request->bus,
                'id_chauffeur' => $request->chauffeur,
                'id_ligne' => $request->ligne,
                'time_day' => Carbon::createFromFormat('Y-m-d H:i', $request->day . ' ' . $request->time)->toDateTimeString(),
                'place' => $request->place,
                'description' => $request->description,
                'pertes' => $request->pertes,
                'photos' => json_encode($imagePaths),
            ]);

            return redirect()->back()->with('success', 'Déclaration enregistrée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Une erreur s\'est produite : ' . $e->getMessage()]);
        }
    }
    public function judiciaire_suivre()
    {
        $declarations = declaration_judiciaire::all();
        $declarationsmonth = declaration_judiciaire::whereMonth('time_day', date('m'))
            ->whereYear('time_day', date('Y'))
            ->get();
        return view("judiciaire.suivie_declaration", compact(['declarations', 'declarationsmonth']));
    }
    public function handle_caat($id, $date)
    {
        $declaration = declaration_judiciaire::find($id);
        if ($declaration) {
            $declaration->caat = true;
            $declaration->date_caat = $date;
            $declaration->update();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    public function handle_paye($id, $date, $montant)
    {
        $declaration = declaration_judiciaire::find($id);
        if ($declaration) {
            $declaration->date_paye = $date;
            $declaration->paye = true;
            $declaration->paye_montant = $montant;
            $declaration->update();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    public function judiciaire_extraire()
    {
        $declarations = declaration_judiciaire::all();
        $declarationsmonth = declaration_judiciaire::whereMonth('time_day', date('m'))
            ->whereYear('time_day', date('Y'))
            ->get();
        return view("judiciaire.extraire", compact(['declarations']));
    }

    public function etat_accident(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required',
        ]);
        $month = $request->input('month');
        $year = $request->input('year');
        $months_ar_array = [
            1 => 'جانفي',
            2 => 'فيفري',
            3 => 'مارس',
            4 => 'أفريل',
            5 => 'ماي',
            6 => 'جوان',
            7 => 'جويلية',
            8 => 'أوت',
            9 => 'سبتمبر',
            10 => 'أكتوبر',
            11 => 'نوفمبر',
            12 => 'ديسمبر'
        ];
        if ($month == 0) {
            $firstDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->startOfYear()->format('Y-m-d');
            $lastDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->endOfYear()->format('Y-m-d');
            $monthName = 'سنة ' . $year;
        } else {
            $firstDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
            $lastDay = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');
            $monthName = $months_ar_array[$month] . ' ' . $year;
        }
        $declarations = declaration_judiciaire::whereBetween('time_day', [$firstDay, $lastDay])->get();
        // $mpdf = new Mpdf([
        //     'format' => 'A4',
        // ]);
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('theme/fonts/tajwal'),
            ]),
            'fontdata' => $fontData + [ // lowercase letters only in font key
                'tajwal' => [
                    'R' => 'Tajawal-Regular.ttf',
                    'I' => 'Tajawal-medium.ttf',
                ]
            ],
            'default_font' => 'tajwal'
        ]);
        $html = view('judiciaire.etat_accident', compact(['monthName', 'declarations']))->render();
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

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);

        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }

    public function etat_naccident_chauffeur(Request $request)
    {
        $request->validate([
            'year' => 'required',
        ]);
        $year = $request->input('year');
        $firstDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->startOfYear()->format('Y-m-d');
        $lastDay = \Carbon\Carbon::createFromFormat('Y', "{$year}")->endOfYear()->format('Y-m-d');
        $monthName = 'سنة ' . $year;

        $declarations = declaration_judiciaire::whereBetween('time_day', [$firstDay, $lastDay])
        ->select('id_chauffeur', DB::raw('COUNT(*) as count_declarations'))
        ->groupBy('id_chauffeur')
        ->orderByDesc('count_declarations')
        ->get();        
        dd($declarations);
        // $mpdf = new Mpdf([
        //     'format' => 'A4',
        // ]);
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('theme/fonts/tajwal'),
            ]),
            'fontdata' => $fontData + [ // lowercase letters only in font key
                'tajwal' => [
                    'R' => 'Tajawal-Regular.ttf',
                    'I' => 'Tajawal-medium.ttf',
                ]
            ],
            'default_font' => 'tajwal'
        ]);
        $html = view('judiciaire.etat_accident', compact(['monthName', 'declarations']))->render();
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

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);

        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
}
