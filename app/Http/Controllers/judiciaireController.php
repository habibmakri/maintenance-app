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
use App\Models\commission_judiciaire;
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
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;

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
            // 'ligne' => 'required',
            'day' => 'required|date',
            'time' => 'required',
            'place' => 'required|string',
            'adverse' => 'required|string',
            'description' => 'nullable|string',
            'pertes' => 'nullable|string',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif', //|max:4096',
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
                'adverse' => $request->adverse,
                'responsability' => null,
                'decision' => null,
                'description' => $request->description,
                'pertes' => $request->pertes,
                'photos' => json_encode($imagePaths),
            ]);

            return redirect()->back()->with('success', 'Déclaration enregistrée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Une erreur s\'est produite : ' . $e->getMessage()]);
        }
    }
    public function do_judiciaire_photos(Request $request)
    {
        $validatedData = $request->validate([
            'fichedeclaration_id' => 'required',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif', //|max:4096',
        ]);

        try {
            $fiche = declaration_judiciaire::findOrFail($request->fichedeclaration_id);
            $imagePaths = [];

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $imageName = time() . '_' . explode(" ", $fiche->time_day)[0] . '_' . $fiche->bus->name . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('judiciaire_img', $imageName, 'public');
                    $imagePaths[] = 'storage/' . $path;
                }
            }
            $fiche->photos = json_encode($imagePaths);
            $fiche->update();

            return redirect()->back()->with('success', 'Photos enregistrée avec succès.');
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
        $declarations = declaration_judiciaire::orderBy('number')->get();
        $declarationsmonth = declaration_judiciaire::whereMonth('time_day', date('m'))
            ->whereYear('time_day', date('Y'))
            ->orderBy('number')
            ->get();
        return view("judiciaire.extraire", compact(['declarations']));
    }
    public function judiciaire_commission()
    {
        $declarations = declaration_judiciaire::where('commission_id', null)->get();
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $commissionsthisyear = commission_judiciaire::with(['declarations.chauffeur', 'declarations.bus'])->whereBetween('date', [$startOfYear, $endOfYear])->get();
        $commissions = commission_judiciaire::with(['declarations.chauffeur', 'declarations.bus'])->get();
        return view("judiciaire.commission", compact(['declarations', 'commissions', 'commissionsthisyear']));
    }
    public function add_judiciaire_commission(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date'],
            'time' => ['required'],
            'timeend' => ['required'],
            'number' => ['required'],
        ]);
        $members_roles = $request->input('members', []);
        $members_names = $request->input('member_quantities', []);
        $mergedmembers = [];
        foreach ($members_names as $index => $pieceId) {
            if (isset($members_roles[$index])) {
                $mergedmembers[$pieceId] = $members_roles[$index];
            }
        }
        // dd($request, $mergedmembers);
        $commission = commission_judiciaire::create(
            [
                "date" => $request->date,
                "time" => $request->time,
                "endtime" => $request->timeend,
                "number" => $request->number,
                "members" => !empty($mergedmembers) ? strval(json_encode($mergedmembers, JSON_UNESCAPED_UNICODE)) : null,
            ]
        );
        $declaration_ids = $request->input('declaration_ids', []);
        $responsabilities = $request->input('responsability', []);
        $decisions = $request->input('decision', []);
        foreach ($declaration_ids as $index => $pieceId) {
            $declaration = declaration_judiciaire::find($pieceId);
            if ($responsabilities[$index] == "true") {
                $declaration->responsability = true;
            } else {
                $declaration->responsability = false;
            }
            $declaration->decision = $decisions[$index];
            $declaration->commission_id = $commission->id;
            $declaration->save();
        }
        return redirect()->back()->with('success', 'Commision créé avec succes.');
    }
    function numberToArabicWords($number)
    {
        $units = [
            0 => 'صفر',
            1 => 'واحد',
            2 => 'اثنان',
            3 => 'ثلاثة',
            4 => 'أربعة',
            5 => 'خمسة',
            6 => 'ستة',
            7 => 'سبعة',
            8 => 'ثمانية',
            9 => 'تسعة'
        ];

        $tens = [
            10 => 'عشرة',
            20 => 'عشرون',
            30 => 'ثلاثون',
            40 => 'أربعون',
            50 => 'خمسون',
            60 => 'ستون',
            70 => 'سبعون',
            80 => 'ثمانون',
            90 => 'تسعون'
        ];

        $exceptions = [
            11 => 'أحد عشر',
            12 => 'اثنا عشر',
            13 => 'ثلاثة عشر',
            14 => 'أربعة عشر',
            15 => 'خمسة عشر',
            16 => 'ستة عشر',
            17 => 'سبعة عشر',
            18 => 'ثمانية عشر',
            19 => 'تسعة عشر'
        ];

        if ($number <= 9) {
            return $units[$number];
        } elseif (isset($exceptions[$number])) {
            return $exceptions[$number];
        } elseif ($number % 10 == 0) {
            return $tens[$number];
        } else {
            $tensPart = intval($number / 10) * 10;
            $unitsPart = $number % 10;

            return $units[$unitsPart] . ' و ' . $tens[$tensPart];
        }
    }
    function numberToArabicYear($year)
    {
        $units = [
            0 => 'صفر',
            1 => 'واحد',
            2 => 'اثنان',
            3 => 'ثلاثة',
            4 => 'أربعة',
            5 => 'خمسة',
            6 => 'ستة',
            7 => 'سبعة',
            8 => 'ثمانية',
            9 => 'تسعة'
        ];

        $tens = [
            10 => 'عشرة',
            20 => 'عشرون',
            30 => 'ثلاثون',
            40 => 'أربعون',
            50 => 'خمسون',
            60 => 'ستون',
            70 => 'سبعون',
            80 => 'ثمانون',
            90 => 'تسعون'
        ];

        $exceptions = [
            11 => 'أحد عشر',
            12 => 'اثنا عشر',
            13 => 'ثلاثة عشر',
            14 => 'أربعة عشر',
            15 => 'خمسة عشر',
            16 => 'ستة عشر',
            17 => 'سبعة عشر',
            18 => 'ثمانية عشر',
            19 => 'تسعة عشر'
        ];

        if ($year >= 2000 && $year <= 2099) {
            $remaining = $year - 2000;

            if ($remaining == 0) {
                return 'ألفان';
            } elseif (isset($exceptions[$remaining])) {
                return 'ألفان و ' . $exceptions[$remaining];
            } elseif ($remaining < 10) {
                return 'ألفان و ' . $units[$remaining];
            } else {
                $tensPart = intval($remaining / 10) * 10;
                $unitsPart = $remaining % 10;

                if ($unitsPart == 0) {
                    return 'ألفان و ' . $tens[$tensPart];
                } else {
                    return 'ألفان و ' . $units[$unitsPart] . ' و ' . $tens[$tensPart];
                }
            }
        }

        return (string) $year;
    }
    function numberToOrdinalArabic($number)
    {
        $ordinalNumbers = [
            1 => 'الأول',
            2 => 'الثاني',
            3 => 'الثالث',
            4 => 'الرابع',
            5 => 'الخامس',
            6 => 'السادس',
            7 => 'السابع',
            8 => 'الثامن',
            9 => 'التاسع',
            10 => 'العاشر',
            11 => 'الحادي عشر',
            12 => 'الثاني عشر',
            13 => 'الثالث عشر',
            14 => 'الرابع عشر',
            15 => 'الخامس عشر',
            16 => 'السادس عشر',
            17 => 'السابع عشر',
            18 => 'الثامن عشر',
            19 => 'التاسع عشر',
            20 => 'العشرون',
            21 => 'الحادي والعشرون',
            22 => 'الثاني والعشرون',
            23 => 'الثالث والعشرون',
            24 => 'الرابع والعشرون',
            25 => 'الخامس والعشرون',
            26 => 'السادس والعشرون',
            27 => 'السابع والعشرون',
            28 => 'الثامن والعشرون',
            29 => 'التاسع والعشرون',
            30 => 'الثلاثون',
            31 => 'الحادي والثلاثون'
        ];

        return $ordinalNumbers[$number] ?? $number;
    }
    function dateToArabicLetters($date)
    {
        $months = [
            'January' => 'يناير',
            'February' => 'فبراير',
            'March' => 'مارس',
            'April' => 'أبريل',
            'May' => 'مايو',
            'June' => 'يونيو',
            'July' => 'يوليو',
            'August' => 'أغسطس',
            'September' => 'سبتمبر',
            'October' => 'أكتوبر',
            'November' => 'نوفمبر',
            'December' => 'ديسمبر'
        ];

        $carbonDate = Carbon::parse($date);
        $day = self::numberToOrdinalArabic($carbonDate->day); // Numéro du jour en arabe ordinal
        $month = $months[$carbonDate->format('F')];
        $year = self::numberToArabicYear($carbonDate->year); // Année en lettres arabes

        return "$day $month $year";
    }
    public function generate_commission_word(Request $request)
    {
        // $request->validate([
        //     'commision_id' => 'required',
        // ]);
        $commission = commission_judiciaire::find($request['commission_id']);
        $accidents = $commission->declarations;
        $templatePath = storage_path('app/public/word/Template_commission.docx');
        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('number',  date('Y', strtotime($commission->date)) . '-' . $commission->number);
        $templateProcessor->setValue('date', $commission->date);
        $templateProcessor->setValue('year', self::dateToArabicLetters($commission->date));
        $templateProcessor->setValue('time', date('H:i', strtotime($commission->time)));
        $members = json_decode($commission->members, true);
        $president = array_search('رئيس اللجنة', $members);
        $templateProcessor->setValue('president', $president);
        $membersList = [];
        foreach ($members as $name => $role) {
            $membersList[] = [
                'members' =>  $name . ': ' . $role
            ];
        }
        $templateProcessor->cloneRowAndSetValues('members', $membersList);
        $firstdate = $accidents[0]->time_day;
        $lastdate = $accidents[0]->time_day;
        foreach ($accidents as $accident) {
            if ($accident->time_day >= $lastdate) {
                $lastdate = $accident->time_day;
            }
            if ($accident->time_day <= $firstdate) {
                $firstdate = $accident->time_day;
            }
        }
        $templateProcessor->setValue('firstaccident', Carbon::parse($firstdate)->format('Y-m-d'));
        $templateProcessor->setValue('lastaccident', Carbon::parse($lastdate)->format('Y-m-d'));
        $templateProcessor->setValue('accidents number', self::numberToArabicWords(count($accidents)));
        $tableData = [];
        foreach ($accidents as $accident) {
            if ($accident->id_chauffeur == 80) {
                $resposablity = $accident->responsability ? 'من العامل' : 'ليس من العامل';
            } else {
                $resposablity = $accident->responsability ? 'من اسائق' : 'ليس من السائق';
            }
            $tableData[] = [
                'numberac'  => $accident->number . '-' . date('Y', strtotime($accident->time_day)),
                'dateac' => date('d/m/Y', strtotime($accident->time_day)),
                'bus' => $accident->bus->name,
                'chauffeur' => $accident->chauffeur->name,
                'pertes' => $accident->pertes,
                'responsabilite' => $resposablity,
                'decision' => $accident->decision,
            ];
        }

        $templateProcessor->cloneRowAndSetValues('numberac', $tableData);
        $templateProcessor->setValue('endtime', date('H:i', strtotime($commission->endtime)));

        $membersList = [];
        $pairList = [];
        $impairList = [];
        $count = 0;
        foreach ($members as $name => $role) {
            if ($count % 2 == 0) {
                $pairList[] = $name . ': ' . $role;
            } else {
                $impairList[] = $name . ': ' . $role;
            }
            $count++;
        }
        $maxCount = max(count($pairList), count($impairList));
        $pairList = array_pad($pairList, $maxCount, '');
        $impairList = array_pad($impairList, $maxCount, '');

        for ($i = 0; $i < $maxCount; $i++) {
            $membersList[] = [
                "pair" => $pairList[$i],
                "impair" => $impairList[$i]
            ];
        }

        $templateProcessor->cloneRowAndSetValues('pair', $membersList);


        $fileName = "محظر إجتماع لجنة الحوادث_{}.docx";
        $fileName = "محظر إجتماع لجنة الحوادث" . $accident->number . '-' . date('Y', strtotime($accident->time_day)) . ".docx";
        $tempFile = tempnam(sys_get_temp_dir(), 'word') . '.docx';
        $templateProcessor->saveAs($tempFile);
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
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

        // $declarations = declaration_judiciaire::whereBetween('time_day', [$firstDay, $lastDay])
        //     ->select('id_chauffeur', DB::raw('COUNT(*) as count_declarations'))
        //     ->groupBy('id_chauffeur')
        //     ->orderByDesc('count_declarations')
        //     ->get();
        $declarations = declaration_judiciaire::whereBetween('time_day', [$firstDay, $lastDay])
            ->select(
                'id_chauffeur',
                DB::raw('COUNT(*) as count_declarations'),
                DB::raw("SUM(CASE WHEN responsability = true THEN 1 ELSE 0 END) as count_true"),
                DB::raw("SUM(CASE WHEN responsability = false THEN 1 ELSE 0 END) as count_false")
            )
            ->groupBy('id_chauffeur')
            ->orderByDesc('count_declarations')
            ->get()
            ->keyBy('id_chauffeur');
        // dd($declarations[2]->count_true);
        // dd($declarations);
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
        $html = view('judiciaire.nbaccident_chauffeur', compact(['monthName', 'declarations']))->render();
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
        $nomfichier = 'عدد الحوادث حسب السائقين سنة'.$year.'.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);

        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
}
