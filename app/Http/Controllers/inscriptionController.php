<?php

namespace App\Http\Controllers;

use App\Models\taxis_prov;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class inscriptionController extends Controller
{
    public function inscription()
    {
        dd("hello");
    }
    public function taxi_provesoire()
    {
        return view('inscription_formation.taxi_prov');
    }
    function create_pdf($taxi)
    {
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
        // $imagePath = public_path('/LOGO ETUS.png');
        $html = view('inscription_formation.recu_taxi_prov', compact(['taxi']))->render();
        $mpdf->AddPage();
        // $mpdf->Image($imagePath, 20, 20, 20, 20, 'png');
        $mpdf->SetY(10);
        date_default_timezone_set('Africa/Algiers');
        //         $htmlFooter = "
        // <div style='border-top: 1px solid black; padding-top: 5px; text-align: center; font-size: 14px;'>
        //     <div>المؤسسة العمومية للنقل الحضري وشبه الحضري سيدي بلعباس</div>
        //     <div>048764072 - طريق معسكر مطول</div>
        // </div>
        // ";
        $htmlFooter = "
<div style='border-top: 1px solid black; padding-top: 5px; text-align: center; font-size: 14px;'>
    <div>مديرية النقل لولاية سيدي بلعباس </div>
</div>
";


        $nomfichier = 'Recu inscription' . $taxi->nom_fr . ' ' . $taxi->prenom_fr . '.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function add_taxi_provesoire(Request $request)
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
            'n_permis' => 'required|string',
            'date_permis' => 'required|date',
            'lieu_permis' => 'required|string',
            'comune_exploi' => 'required|string',
        ]);

        $existingByPhone = taxis_prov::where('phone', $request->phone)->first();
        if ($existingByPhone) {
            return back()->withErrors(['phone' => 'رقم الهاتف مستخدم مسبقًا.'])->withInput();
        }

        $existingByNin = taxis_prov::where('nin', $request->nin)->first();
        if ($existingByNin) {
            return back()->withErrors(['nin' => 'هذا الرقم الوطني مسجل مسبقًا.'])->withInput();
        }

        $datePermis = \Carbon\Carbon::parse($request->date_permis);
        if ($datePermis->diffInDays(now()) < 730) {
            return back()->withErrors(['date_permis' => 'تاريخ رخصة السياقة يجب أن يكون أقدم من عامين.'])->withInput();
        }

        $birthdate = \Carbon\Carbon::parse($request->birthdate);
        if ($birthdate->diffInDays(now()) < 9131) {
            return back()->withErrors(['birthdate' => 'يجب أن يكون عمر المترشح 25 سنة على الأقل.'])->withInput();
        }

        $taxi =  taxis_prov::create([
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
            'n_permis' => $request->n_permis,
            'date_permis' => $request->date_permis,
            'lieu_permis' => $request->lieu_permis,
            'comune_exploi' => $request->comune_exploi,
            'list' => $request->list,
        ]);
        // self::create_pdf($taxi);
        // return redirect()->back()->with('success', 'تم تسجيل المعلومات بنجاح.');
        session([
            'can_download_pdf' => $taxi->id,
            'pdf_download_expiration' => now()->addMinute()
        ]);
        return redirect()->route('inscription_taxi.success', ['id' => $taxi->id]);
    }

    public function success($id)
    {
        $taxi = taxis_prov::findOrFail($id);
        return view('inscription_formation.success', compact('taxi'));
    }

    public function downloadPdf($id)
    {
        $allowedId = session('can_download_pdf');
        $expiration = session('pdf_download_expiration');

        if (!$allowedId || $allowedId != $id || !$expiration) {
            abort(403, 'انتهت صلاحية رابط التحميل أو الوصول غير مصرح به.');
        }

        $expiration = Carbon::parse($expiration);

        if (now()->greaterThan($expiration)) {
            session()->forget(['can_download_pdf', 'pdf_download_expiration']);
            abort(403, 'انتهت صلاحية رابط التحميل أو الوصول غير مصرح به.');
        }


        $taxi = taxis_prov::findOrFail($id);
        return $this->create_pdf($taxi);
    }
}
