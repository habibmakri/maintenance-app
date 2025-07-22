<?php

namespace App\Http\Controllers;

use App\Models\counters_formation;
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
        // dd($taxis, $tper, $tmar, $tdan);
        return view('formation.formation_sessions', compact(['lists', 'taxis', 'tper', 'tmar', 'tdan']));
    }
    public function do_create_foramtion_sessions(Request $request)
    {
        $validated = $request->validate([
            'participants' => 'required|array|min:1',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'type_insc' => 'required|in:taxis,tper,tmar,tdan',
        ]);
        if ($request->type_insc == "taxis") {
            $formation_number = $this->getNextFormattedNumber('Formation_' . $request->type_insc, $request->date_debut);
            $formation = formation_sessions::create([
                'type' => $request->type_insc,
                'counter' => $formation_number,
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
        }
        return redirect()->back()->with('error', ' Erreur!!');
    }
    public function print_list_session(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required',
        ]);
        // dd($request->list_id);



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
                'valid_date' => Carbon::now()->toDateString(),
            ]);
        } else {
            return back()->with('error', 'Erreur confirmation.');
        }
            return back()->with('success', 'Session '.$session->type.' '.$session->counter.' confirmé.');

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
        }
    }
}
