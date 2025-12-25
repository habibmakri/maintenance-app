<?php

namespace App\Http\Controllers;

use App\Models\autoecole;
use App\Models\autoecole_list;
use App\Models\counters_formation;
use App\Models\entreprise;
use App\Models\formation_sessions;
use App\Models\taxis;
use App\Models\taxis_list;
use App\Models\taxis_prov;
use App\Models\tdan;
use App\Models\tmar;
use App\Models\tper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class formationController extends Controller
{
    public function confirmer_taxis_prov()
    {
        $taxis = taxis_prov::all();
        $list = null;
        return view('formation.confirmation', compact(['taxis', 'list']));
    }

    public function confirmer_taxis()
    {
        $taxis = taxis::all();
        $list = taxis_list::whereNull('valid_date')->first();
        return view('formation.confirmation', compact(['taxis', 'list']));
    }
    public function do_rejet_taxis(Request $request)
    {
        $request->validate([
            'taxi_id' => 'required||exists:taxis,id'
        ]);

        $taxi = taxis::findOrFail($request->taxi_id);
        $taxi->update([
            'rejet' => true
        ]);

        return redirect()->back()->with('success',  $taxi->nom_fr . ' ' . $taxi->prenom_fr . 'rejet avec succés');
    }
    public function do_confirmer_taxis(Request $request)
    {
        $request->validate([
            'taxi_id' => 'required||exists:taxis,id'
        ]);

        $list = taxis_list::whereNull('valid_date')->first();
        $taxi = taxis::findOrFail($request->taxi_id);

        if ($list) {
            $taxi->update([
                'list' => $list->id
            ]);
            return redirect()->back()->with('success',  $taxi->nom_fr . ' ' . $taxi->prenom_fr . 'confirmer avec succés: liste' . $list->counter);
        } else {
            return redirect()->back()->with('error', 'Erreur');
        }
    }
    public function do_confirmer_list(Request $request)
    {
        $request->validate([
            'list_id' => 'required||exists:taxis_list,id'
        ]);

        $list = taxis_list::findOrFail($request->list_id);

        if ($list) {
            $list->update([
                'valid_date' => Carbon::now()->toDateString(),
            ]);
            return redirect()->back()->with('success',  $list->counter . 'confirmer avec succés.');
        } else {
            return redirect()->back()->with('error', 'Erreur');
        }
    }
    public function manage_list_taxis()
    {
        $lists = taxis_list::all();
        $allConfirmed = taxis_list::all()->every(function ($taxi) {
            return !is_null($taxi->valid_date);
        });
        return view('formation.taxis_list', compact(['lists', 'allConfirmed']));
    }
    public function create_list_taxis()
    {
        $date = Carbon::now()->toDateString();
        $allConfirmed = taxis_list::all()->every(function ($taxi) {
            return !is_null($taxi->valid_date);
        });
        if ($allConfirmed) {
            $validation_number = $this->getNextFormattedNumber('List_Taxis', $date);
            taxis_list::create([
                'counter' => $validation_number
            ]);
            return redirect()->back()->with('success',  'Liste créé');
        }
        return redirect()->back()->with('error',  'erreur');
    }
    public function print_list_taxis(Request $request)
    {
        $validated = $request->validate([
            'list_id' => 'required',
        ]);
        // dd($request->list_id);



        $list = taxis_list::find($request->list_id);
        // $mpdf = new Mpdf([
        //     'format' => 'A4',
        // ]);
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('theme/fonts/sakkal-majalla-2'),
            ]),
            'directionality' => 'rtl',
            'fontdata' => $fontData + [
                'sakkal' => [
                    'R' => 'majalla.ttf',
                ],
            ],
            'default_font' => 'sakkal',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        $html = view('formation.list_taxis_pdf', compact(['list']))->render();
        $mpdf->AddPage();
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: left; font-size: 12px;' >
        صفحة {PAGENO} من {nbpg}  <span>  ثم إستخراج الملف في $currentdate </span>
        </div>
        ";
        $nomfichier = $list->counter . 'لائحة سيارات أجرة رقم.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);

        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }


    public function inscription_autoecole()
    {
        $taxis = autoecole::all();
        $list = autoecole_list::whereNull('valid_date')->first();
        return view('formation.autoecole', compact(['taxis', 'list']));
    }
    public function manage_list_autoecole()
    {
        $lists = autoecole_list::all();
        $allConfirmed = autoecole_list::all()->every(function ($taxi) {
            return !is_null($taxi->valid_date);
        });
        return view('formation.autoecole_list', compact(['lists', 'allConfirmed']));
    }

    public function create_list_autoecole()
    {
        $date = Carbon::now()->toDateString();
        $allConfirmed = autoecole_list::all()->every(function ($taxi) {
            return !is_null($taxi->valid_date);
        });
        if ($allConfirmed) {
            $validation_number = $this->getNextFormattedNumber('List_Autoecole', $date);
            autoecole_list::create([
                'counter' => $validation_number
            ]);
            return redirect()->back()->with('success',  'Liste créé');
        }
        return redirect()->back()->with('error',  'erreur');
    }

    public function do_confirmer_autoecole(Request $request)
    {
        $request->validate([
            'taxi_id' => 'required||exists:autoecole,id'
        ]);

        $list = autoecole_list::whereNull('valid_date')->first();
        $taxi = autoecole::findOrFail($request->taxi_id);

        if ($list) {
            $taxi->update([
                'list' => $list->id
            ]);
            return redirect()->back()->with('success',  $taxi->nom_fr . ' ' . $taxi->prenom_fr . 'confirmer avec succés: liste' . $list->counter);
        } else {
            return redirect()->back()->with('error', 'Erreur');
        }
    }

    public function do_confirmer_list_autoecole(Request $request)
    {
        $request->validate([
            'list_id' => 'required||exists:autoecole_list,id'
        ]);

        $list = autoecole_list::findOrFail($request->list_id);

        if ($list) {
            $list->update([
                'valid_date' => Carbon::now()->toDateString(),
            ]);
            return redirect()->back()->with('success',  $list->counter . 'confirmer avec succés.');
        } else {
            return redirect()->back()->with('error', 'Erreur');
        }
    }

    public function ajouter_autoecole(Request $request)
    {
        $validatedData = $request->validate([
            'nin' => 'required|digits:18',
            'phone' => 'required|regex:/^0[5-7][0-9]{8}$/',
            'gender' => 'required',
            'nom_ar' => 'required|string',
            'prenom_ar' => 'required|string',
            'nom_fr' => 'nullable|string',
            'prenom_fr' => 'nullable|string',
            'birthdate' => 'required|date',
            'birthplace' => 'required|string',
            'adresse' => 'required|string',
            'email' => 'nullable|email',
            'type' => 'nullable',
        ]);

        $existingByPhone = autoecole::where('phone', $request->phone)->first();
        if ($existingByPhone) {
            return back()->withErrors(['phone' => 'رقم الهاتف مستخدم مسبقًا.'])->withInput();
        }

        $existingByNin = autoecole::where('nin', $request->nin)->first();
        if ($existingByNin) {
            return back()->withErrors(['nin' => 'هذا الرقم الوطني مسجل مسبقًا.'])->withInput();
        }

        $taxi =  autoecole::create([
            'nin' => $request->nin,
            'inscription_time' => Carbon::now('Africa/Algiers'),
            'phone' => $request->phone,
            'gender' => $request->gender,
            'nom_ar' => $request->nom_ar,
            'prenom_ar' => $request->prenom_ar,
            'nom_fr' => $request->nom_fr,
            'prenom_fr' => $request->prenom_fr,
            'birthdate' => $request->birthdate,
            'birthplace' => $request->birthplace,
            'adresse' => $request->adresse,
            'email' => $request->email,
            'type' => $request->type,
            'list' => null,
        ]);
        return redirect()->back()->with('success', "Autoecole Ajouté");
    }

    public function autoecole()
    {
        $taxis = autoecole::query()->leftJoin('autoecole_list', 'autoecole.list', '=', 'autoecole_list.id')
            ->whereNotNull('autoecole_list.valid_date')->select('autoecole.*')->get();
        $type_insc = "Moniteur Auto Ecole";
        // dd($taxis);
        return view('formation.participants_dynamique', compact(['type_insc', 'taxis']));
    }





    public function taxis()
    {
        $taxis = taxis::query()->leftJoin('taxis_list', 'taxis.list', '=', 'taxis_list.id')
            ->whereNotNull('taxis_list.valid_date')->select('taxis.*')->get();
        $type_insc = "Carnet Taxi";
        // dd($taxis);
        return view('formation.participants_dynamique', compact(['type_insc', 'taxis']));
    }

    // public function formation_taxi()
    // {
    //     $taxis = taxis::all();
    //     $type_insc = "Tansport personne";
    //     return view('formation.participants_dynamique', compact(['type_insc', 'taxis']));
    // }
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
    public function transport_entreprises()
    {
        $taxis = entreprise::with(['count_tper_emps', 'count_tdan_emps', 'count_tmar_emps'])->get();
        $type_insc = "Entreprises";
        // dd($taxis[2]->getTotalEmps() ,$taxis[2]->getNonPaidEmps());
        return view('formation.entreprises', compact(['type_insc', 'taxis']));
    }
    public function entrepise_paiement(Request $request)
    {
        $request->validate([
            'enteprise_id' => 'required',
            'date' => 'required',
            'cheque_number' => 'required',
            'emp_id' => 'required',
            'emp_type' => 'required',
            'montant' => 'required',
        ]);
        $emp_ids = $request->emp_id;
        $emp_types = $request->emp_type;
        $montants = $request->montant;
        $montants_total = 0;
        $entreprise = entreprise::find($request->enteprise_id);
        if (array_sum($montants) == 0) {
            return redirect()->back()->with('error', 'Montant 0');
        };
        if (!$entreprise) {
            return redirect()->back()->with('error', 'Erreur invalide.');
        }
        $payment_number = $this->getNextFormattedNumber('Payment', $request->date);
        $count = count($emp_ids);

        for ($i = 0; $i < $count; $i++) {
            $emp_id = $emp_ids[$i];
            $emp_type = $emp_types[$i];
            $montant = $montants[$i];

            if ($montant == 0) {
                continue;
            }
            if ($emp_type == 'tper') {
                $emp = tper::findOrFail($emp_id);
                $validation_number = $this->getNextFormattedNumber('Tansport_personne', $request->date);
                $emp->update([
                    'validation_number' => $validation_number,
                    'payment_number' => $payment_number,
                    'cheque_number' => $request->cheque_number,
                    'montant_paiement' => $montant,
                    'date_paiement' => $request->date,
                ]);
            } elseif ($emp_type == 'tmar') {
                $emp = tmar::findOrFail($emp_id);
                $validation_number = $this->getNextFormattedNumber('Tansport_Marchendise', $request->date);
                $emp->update([
                    'validation_number' => $validation_number,
                    'payment_number' => $payment_number,
                    'cheque_number' => $request->cheque_number,
                    'montant_paiement' => $montant,
                    'date_paiement' => $request->date,
                ]);
            } elseif ($emp_type == 'tdan') {
                $emp = tdan::findOrFail($emp_id);
                $validation_number = $this->getNextFormattedNumber('Tansport_Materieux_Dangereux', $request->date);
                $emp->update([
                    'validation_number' => $validation_number,
                    'payment_number' => $payment_number,
                    'cheque_number' => $request->cheque_number,
                    'montant_paiement' => $montant,
                    'date_paiement' => $request->date,
                ]);
            } else {
                return redirect()->back()->with('error', 'Erreur invalide.');
            }
            $montants_total += $montant;
        }
        $payments = json_decode($entreprise->payments, true) ?? [];

        $payments[] = [
            'payment_number' => $payment_number,
            'date' => $request->date,
            'montant' => $montants_total,
        ];
        $entreprise->payments = json_encode($payments);
        $entreprise->save();

        return redirect()->back()->with('success', 'Payement validé avec succés.');
    }
    public function print_entrepise_details(Request $request)
    {
        $validated = $request->validate([
            'id_entreprise' => 'required|exists:entreprise,id',
        ]);
        $item = entreprise::find($validated['id_entreprise']);
        // dd($item);
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('theme/fonts/sakkal-majalla-2'),
            ]),
            'directionality' => 'rtl',
            'fontdata' => $fontData + [
                'sakkal' => [
                    'R' => 'majalla.ttf',
                ],
            ],
            'default_font' => 'sakkal',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        $html = view('formation.detail_entreprise_pdf', compact(['item']))->render();
        $imagePath = public_path('/LOGO ETUS.png');
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 24, 14, 35, 35, 'png');
        $mpdf->SetY(10);
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: left; font-size: 12px;' >
        صفحة {PAGENO} من {nbpg}  <span>  ثم إستخراج الملف في $currentdate </span>
        </div>
        ";
        $nomfichier = 'مؤسسة ' . $item->name . '.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);

        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function foramtion_sessions()
    {
        $lists = formation_sessions::all();
        $taxis = taxis::query()
            ->whereNotNull('payment_number')
            ->whereNull('session_id')->get();
        $tper = tper::query()
            ->whereNotNull('payment_number')
            ->whereNull('session_id')->get();
        $tmar = tmar::query()
            ->whereNotNull('payment_number')
            ->whereNull('session_id')->get();
        $tdan = tdan::query()
            ->whereNotNull('payment_number')
            ->whereNull('session_id')->get();
        $mae = autoecole::query()
            ->whereNotNull('payment_number')
            ->whereNull('session_id')->get();
        // dd($taxis, $tper, $tmar, $tdan);
        return view('formation.formation_sessions', compact(['lists', 'taxis', 'tper', 'tmar', 'tdan','mae']));
    }
    public function do_create_foramtion_sessions(Request $request)
    {
        $validated = $request->validate([
            'participants' => 'required|array|min:1',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'type_insc' => 'required|in:taxis,tper,tmar,tdan,mae',
        ]);
        if ($request->type_insc == "taxis") {
            $formation_number = $this->getNextFormattedNumber('Formation_' . $request->type_insc, $request->date_debut);
            $formation = formation_sessions::create([
                'type' => $request->type_insc,
                'counter' => $formation_number,
                'groups' => $request->groups,
                'profs' => json_encode($request->profs, JSON_UNESCAPED_UNICODE),
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
            ]);
            foreach ($request->participants as $participant) {
                $record = taxis::find($participant);
                if ($record) {
                    $record->update([
                        'session_id' => $formation->id,
                    ]);
                }
            }
            return redirect()->back()->with('succes', 'Formation' . $request->type_insc . ' ' . $formation_number . 'Créé avec succes.');
        } elseif ($request->type_insc == "tper") {
            $formation_number = $this->getNextFormattedNumber('Formation_' . $request->type_insc, $request->date_debut);
            $formation = formation_sessions::create([
                'type' => $request->type_insc,
                'counter' => $formation_number,
                'groups' => $request->groups,
                'profs' => json_encode($request->profs, JSON_UNESCAPED_UNICODE),
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
            ]);
            foreach ($request->participants as $participant) {
                $record = tper::find($participant);
                if ($record) {
                    $record->update([
                        'session_id' => $formation->id,
                    ]);
                }
            }
            return redirect()->back()->with('succes', 'Formation' . $request->type_insc . ' ' . $formation_number . 'Créé avec succes.');
        } elseif ($request->type_insc == "tmar") {
            $formation_number = $this->getNextFormattedNumber('Formation_' . $request->type_insc, $request->date_debut);
            $formation = formation_sessions::create([
                'type' => $request->type_insc,
                'counter' => $formation_number,
                'groups' => $request->groups,
                'profs' => json_encode($request->profs, JSON_UNESCAPED_UNICODE),
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
            ]);
            foreach ($request->participants as $participant) {
                $record = tmar::find($participant);
                if ($record) {
                    $record->update([
                        'session_id' => $formation->id,
                    ]);
                }
            }
            return redirect()->back()->with('succes', 'Formation' . $request->type_insc . ' ' . $formation_number . 'Créé avec succes.');
        } elseif ($request->type_insc == "tdan") {
            $formation_number = $this->getNextFormattedNumber('Formation_' . $request->type_insc, $request->date_debut);
            $formation = formation_sessions::create([
                'type' => $request->type_insc,
                'counter' => $formation_number,
                'groups' => $request->groups,
                'profs' => json_encode($request->profs, JSON_UNESCAPED_UNICODE),
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
            ]);
            foreach ($request->participants as $participant) {
                $record = tdan::find($participant);
                if ($record) {
                    $record->update([
                        'session_id' => $formation->id,
                    ]);
                }
            }
            return redirect()->back()->with('succes', 'Formation' . $request->type_insc . ' ' . $formation_number . 'Créé avec succes.');
        }elseif ($request->type_insc == "mae") {
            $formation_number = $this->getNextFormattedNumber('Formation_' . $request->type_insc, $request->date_debut);
            $formation = formation_sessions::create([
                'type' => $request->type_insc,
                'counter' => $formation_number,
                'groups' => $request->groups,
                'profs' => json_encode($request->profs, JSON_UNESCAPED_UNICODE),
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
            ]);
            foreach ($request->participants as $participant) {
                $record = autoecole::find($participant);
                if ($record) {
                    $record->update([
                        'session_id' => $formation->id,
                    ]);
                }
            }
            return redirect()->back()->with('succes', 'Formation' . $request->type_insc . ' ' . $formation_number . 'Créé avec succes.');
        }
        return redirect()->back()->with('error', ' Erreur!!');
    }
    public function print_list_session(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required',
        ]);
        $list = formation_sessions::find($request->session_id);

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('theme/fonts/sakkal-majalla-2'),
            ]),
            'directionality' => 'rtl',
            'fontdata' => $fontData + [
                'sakkal' => [
                    'R' => 'majalla.ttf',
                ],
            ],
            'default_font' => 'sakkal',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        $html = view('formation.list_session_pdf', compact(['list']))->render();
        $mpdf->AddPage();
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: left; font-size: 12px;' >
        صفحة {PAGENO} من {nbpg}  <span>  ثم إستخراج الملف في $currentdate </span>
        </div>
        ";
        $nomfichier = $list->type . ' ' . $list->counter . 'لائحة تكوين رقم.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);

        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function print_detail_session(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required',
        ]);
        $list = formation_sessions::find($request->session_id);
        // $mpdf = new Mpdf([
        //     'format' => 'A4',
        // ]);
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('theme/fonts/sakkal-majalla-2'),
            ]),
            'directionality' => 'rtl',
            'fontdata' => $fontData + [
                'sakkal' => [
                    'R' => 'majalla.ttf',
                ],
            ],
            'default_font' => 'sakkal',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        $html = view('formation.detail_session_pdf', compact(['list']))->render();
        $imagePath = public_path('/LOGO ETUS.png');
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 230, 9, 30, 30, 'png');
        $mpdf->SetY(10);
        // $mpdf->AddPage();
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: left; font-size: 12px;' >
        صفحة {PAGENO} من {nbpg}  <span>  ثم إستخراج الملف في $currentdate </span>
        </div>
        ";
        $nomfichier = $list->type . ' ' . $list->counter . 'لائحة تكوين رقم.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);

        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function print_delibiration(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required',
        ]);
        $list = formation_sessions::find($request->session_id);
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'format' => 'A4-L',
            'fontDir' => array_merge($fontDirs, [
                public_path('theme/fonts/sakkal-majalla-2'),
            ]),
            'directionality' => 'rtl',
            'fontdata' => $fontData + [
                'sakkal' => [
                    'R' => 'majalla.ttf',
                ],
            ],
            'default_font' => 'sakkal',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        $html = view('formation.delibiration_session_pdf', compact(['list']))->render();
        $imagePath = public_path('/LOGO ETUS.png');
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 30, 15, 28, 28, 'png');
        $mpdf->SetY(10);
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: left; font-size: 12px;' >
        صفحة {PAGENO} من {nbpg}  <span>  ثم إستخراج الملف في $currentdate </span>
        </div>
        ";
        $nomfichier = $list->type . ' ' . $list->counter . 'لائحة تكوين رقم.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);

        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }

    public function print_diplomes(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required',
        ]);
        $list = formation_sessions::find($request->session_id);
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'format' => 'A4-L',
            'fontDir' => array_merge($fontDirs, [
                public_path('theme/fonts/sakkal-majalla-2'),
                public_path('theme/fonts/Amiri'),
            ]),
            'directionality' => 'rtl',
            'fontdata' => $fontData + [
                'sakkal' => [
                    'R' => 'majalla.ttf',
                ],
                'amiri' => [
                    'R' => 'amiri.ttf',
                ],
            ],
            'default_font' => 'amiri',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);
        $items = $list->count_models($list->type)->get();
        $bgImage = public_path('Diplome.png');
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: left; font-size: 12px;' >
        صفحة {PAGENO} من {nbpg}  <span>  ثم إستخراج الملف في $currentdate </span>
        </div>
        ";
        $nomfichier = $list->type . ' ' . $list->counter . 'لائحة تكوين رقم.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);


        foreach ($items as $index => $item) {
            $notes = json_decode($item->notes, true);
            $total = 0;
            $count = count($notes);
            foreach ($notes as $matiere => $details) {
                $exam = $details['إمتحان'] ?? 0;
                $moazaba = $details['مواضبة'] ?? 0;
                $prof = $profs[$matiere] ?? '';
                $moyenne = ($exam + $moazaba) / 2;
                $total += $moyenne;
            }
            $moyenneGenerale = $total / $count;
            if ($moyenneGenerale < 8) {
                continue;
            }
            $mpdf->AddPage();
            $mpdf->Image(
                $bgImage,
                0,
                0,
                297,
                210,
                'jpg',
                '',
                true,
                false
            );
            $mpdf->SetY(32);
            $html = view('formation.diplome_pdf', compact('list', 'item'))->render();
            $mpdf->WriteHTML($html);
        }

        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function print_delibiration_fiches(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required',
        ]);

        $list = formation_sessions::find($request->session_id);

        // mPDF config
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('theme/fonts/sakkal-majalla-2'),
            ]),
            'directionality' => 'rtl',
            'fontdata' => $fontData + [
                'sakkal' => [
                    'R' => 'majalla.ttf',
                ],
            ],
            'default_font' => 'sakkal',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        // Loop through all persons instead of just one
        $items = $list->count_models($list->type)->get();

        $imagePath = public_path('/LOGO ETUS.png');

        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: left; font-size: 12px;'>
            صفحة {PAGENO} من {nbpg} <span>  ثم إستخراج الملف في $currentdate </span>
        </div>
    ";

        $mpdf->SetHTMLFooter($htmlFooter);

        $students = $list->count_models($list->type)->get();
        $totalStudents = $students->count();
        $groupSize = ceil($totalStudents / $list->groups);
        $group = 1;

        foreach ($items as $index => $item) {

            $mpdf->AddPage();
            if ($index > 0 && $index % $groupSize === 0) {
                $group++;
            }
            $mpdf->Image($imagePath, 230, 9, 30, 30, 'png');
            $mpdf->SetY(10);

            $html = view('formation.fiches_delibiration_pdf', compact('list', 'item', 'group'))->render();

            $mpdf->WriteHTML($html);
        }

        $nomfichier = $list->type . ' ' . $list->counter . ' لائحة تكوين رقم.pdf';

        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function print_notes_paper(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required',
        ]);

        $list = formation_sessions::find($request->session_id);
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('theme/fonts/sakkal-majalla-2'),
            ]),
            'directionality' => 'rtl',
            'fontdata' => $fontData + [
                'sakkal' => [
                    'R' => 'majalla.ttf',
                ],
            ],
            'default_font' => 'sakkal',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        $imagePath = public_path('/LOGO ETUS.png');
        $students = $list->count_models($list->type)->get();
        $totalStudents = $students->count();
        $groupSize = ceil($totalStudents / $list->groups);
        $group = 1;
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: left; font-size: 12px;' >
        صفحة {PAGENO} من {nbpg}  <span>  ثم إستخراج الملف في $currentdate </span>
        </div>
        ";
        for ($g = 0; $g < $list->groups; $g++) {
            $start = $g * $groupSize;
            $end = min(($g + 1) * $groupSize, $totalStudents);
            $ls = array_slice($students->all(), $start, $end - $start);
            $mpdf->AddPage();

            $mpdf->Image($imagePath, 230, 9, 30, 30, 'png');
            $mpdf->SetY(10);

            $html = view('formation.notes_paper_pdf', compact(['list', 'ls', 'group']))->render();
            $mpdf->WriteHTML($html);
            $mpdf->SetHTMLFooter($htmlFooter);
            $group++;
        }
        $nomfichier = $list->type . ' ' . $list->counter . 'لائحة تكوين رقم.pdf';
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function print_presence_paper(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required',
        ]);

        $list = formation_sessions::find($request->session_id);
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('theme/fonts/sakkal-majalla-2'),
            ]),
            'directionality' => 'rtl',
            'fontdata' => $fontData + [
                'sakkal' => [
                    'R' => 'majalla.ttf',
                ],
            ],
            'default_font' => 'sakkal',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        $imagePath = public_path('/LOGO ETUS.png');
        $students = $list->count_models($list->type)->get();
        $totalStudents = $students->count();
        $groupSize = ceil($totalStudents / $list->groups);
        $group = 1;
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: left; font-size: 12px;' >
        صفحة {PAGENO} من {nbpg}  <span>  ثم إستخراج الملف في $currentdate </span>
        </div>
        ";
        for ($g = 0; $g < $list->groups; $g++) {
            $start = $g * $groupSize;
            $end = min(($g + 1) * $groupSize, $totalStudents);
            $ls = array_slice($students->all(), $start, $end - $start);
            $mpdf->AddPage();

            $mpdf->Image($imagePath, 230, 9, 30, 30, 'png');
            $mpdf->SetY(10);

            $html = view('formation.presence_paper_pdf', compact(['list', 'ls', 'group']))->render();
            $mpdf->WriteHTML($html);
            $mpdf->SetHTMLFooter($htmlFooter);
            $group++;
        }
        $nomfichier = $list->type . ' ' . $list->counter . 'لائحة تكوين رقم.pdf';
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function confirm_session(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required',
            'session_type' => 'required',
        ]);
        $modelMap = [
            'taxis' => \App\Models\taxis::class,
            'tper'  => \App\Models\tper::class,
            'tmar'  => \App\Models\tmar::class,
            'tdan'  => \App\Models\tdan::class,
            'mae'  => \App\Models\autoecole::class,
        ];

        $type = $request->session_type;

        if (isset($modelMap[$type])) {
            $model = $modelMap[$type];

            foreach ($request->participants as $participant) {
                $notes = collect($participant)->except('id');
                $record = $model::find($participant['id']);

                if ($record) {
                    $record->update([
                        'notes' => json_encode($notes, JSON_UNESCAPED_UNICODE),
                    ]);
                }
            }
            $session = formation_sessions::find($request->session_id);
            $session->update([
                // 'valid_date' => Carbon::now()->toDateString(),
                'valid_date' => $request->confirm_date,
            ]);
        } else {
            return back()->with('error', 'Erreur confirmation.');
        }
        return back()->with('success', 'Session ' . $session->type . ' ' . $session->counter . ' confirmé.');
    }

    public function save_draft(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required',
            'session_type' => 'required',
            'participants' => 'required|array',
        ]);

        $modelMap = [
            'taxis' => \App\Models\taxis::class,
            'tper'  => \App\Models\tper::class,
            'tmar'  => \App\Models\tmar::class,
            'tdan'  => \App\Models\tdan::class,
            'mae'  => \App\Models\autoecole::class,
        ];

        $type = $request->session_type;

        if (!isset($modelMap[$type])) {
            return response()->json(['message' => 'Type inconnu'], 400);
        }

        $model = $modelMap[$type];

        foreach ($request->participants as $participant) {
            $notes = collect($participant)->except('id');
            $record = $model::find($participant['id']);

            if ($record) {
                $record->update([
                    'notes' => json_encode($notes, JSON_UNESCAPED_UNICODE),
                ]);
            }
        }

        return response()->json(['message' => 'Brouillon sauvegardé']);
    }


    function getNextFormattedNumber($typePrefix, $date)
    {
        // $year = $date->year;
        $carbonDate = Carbon::parse($date);
        $year = $carbonDate->year;
        $key = "{$typePrefix}_{$year}";

        $counter = counters_formation::firstOrCreate(['type' => $key], ['last_number' => 0, 'date' => $date]);
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
            'cheque_number' => 'required',
            'somme_paiement' => 'required',
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
        } else if ($request->type_insc == 'Carnet Taxi') {
            $participant = taxis::find($request->id_participant);
        } else if ($request->type_insc == 'Moniteur Auto Ecole') {
            $participant = autoecole::find($request->id_participant);
        }
        // dd($participant);
        if (!$participant) {
            return redirect()->back()->withErrors(['id_participant' => 'Error']);
        }
        $type_insc = str_replace(' ', '_', $request->type_insc);
        $validation_number = $this->getNextFormattedNumber($type_insc, $request->date);
        $payment_number = $this->getNextFormattedNumber('Payment', $request->date);

        $participant->update([
            'payment_number' => $payment_number,
            'validation_number' => $validation_number,
            'cheque_number' => $request->cheque_number,
            'montant_paiement' => $request->somme_paiement,
            'date_paiement' => $request->date,
        ]);
        if ($request->type_insc == 'Tansport personne') {
            return redirect()->back()->with('success', $participant->nom_fr . ' ' . $participant->prenom_fr . ' Validé avec succes!');
        } else if ($request->type_insc == 'Tansport Marchendise') {
            return redirect()->back()->with('success', $participant->nom_fr . ' ' . $participant->prenom_fr . ' Validé avec succes!');
        } else if ($request->type_insc == 'Tansport Materieux Dangereux') {
            return redirect()->back()->with('success', $participant->nom_fr . ' ' . $participant->prenom_fr . ' Validé avec succes!');
        } else if ($request->type_insc == 'Carnet Taxi') {
            return redirect()->back()->with('success', $participant->nom_fr . ' ' . $participant->prenom_fr . ' Validé avec succes!');
        } else if ($request->type_insc == 'Moniteur Auto Ecole') {
            return redirect()->back()->with('success', $participant->nom_fr . ' ' . $participant->prenom_fr . ' Validé avec succes!');
        }
    }

    public function print_attestation(Request $request)
    {
        $validated = $request->validate([
            'id_participant' => 'required',
            'type_insc' => 'required',
        ]);
        $item = null;
        // dd($request);
        if ($request->type_insc == 'Tansport personne') {
            $item = tper::find($request->id_participant);
        } else if ($request->type_insc == 'Tansport Marchendise') {
            $item = tmar::find($request->id_participant);
        } else if ($request->type_insc == 'Tansport Materieux Dangereux') {
            $item = tdan::find($request->id_participant);
        } else if ($request->type_insc == 'Carnet Taxi') {
            $item = taxis::find($request->id_participant);
        } else if ($request->type_insc == 'Moniteur Auto Ecole') {
            $item = autoecole::find($request->id_participant);
        }
        // $mpdf = new Mpdf([
        //     'format' => 'A4',
        // ]);
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('theme/fonts/sakkal-majalla-2'),
            ]),
            'directionality' => 'rtl',
            'fontdata' => $fontData + [
                'sakkal' => [
                    'R' => 'majalla.ttf',
                ],
            ],
            'default_font' => 'sakkal',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);
        $type_insc = $request->type_insc;
        $html1 = view('formation.attestation_insc_pdf', compact(['item', 'type_insc']))->render();
        $html2 = view('formation.recu_paiement_pdf', compact(['item', 'type_insc']))->render();
        $imagePath = public_path('/LOGO ETUS.png');
        date_default_timezone_set('Africa/Algiers');
        $currentdate = date('H:i:s d-m-Y');
        $htmlFooter = "
        <div style='text-align: left; font-size: 12px;' >
        صفحة {PAGENO} من {nbpg}  <span>  ثم إستخراج الملف في $currentdate </span>
        </div>
        ";
        $nomfichier =  'لائحة تكوين رقم.pdf';
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 230, 9, 30, 30, 'png');
        $mpdf->SetY(10);
        $mpdf->WriteHTML($html1);
        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->AddPage();
        $mpdf->Image($imagePath, 230, 9, 30, 30, 'png');
        $mpdf->SetY(10);
        $mpdf->WriteHTML($html2);
        // $mpdf->AddPage();

        $mpdf->SetHTMLFooter($htmlFooter);

        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
}
