<?php

namespace App\Http\Controllers;

use App\Models\ctechnique_clients;
use App\Models\ctechnique_rating;
use App\Models\ctechniqueclienttypes;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class ctechniqueController extends Controller
{

    public function rate_ctechnique(Request $request)
    {
        return view('ctechnique.rate_us');
    }
    public function add_rate_ctechnique(Request $request)
    {
        if ($request->filled(['service_rating']) || $request->filled(['controler_rating']) || $request->filled(['clean_rating']) || $request->filled(['order_rating']) || $request->filled(['message']) || $request->filled(['phone'])) {
            $rating = new ctechnique_rating();
            $rating->service = $request->input('service_rating', null);
            $rating->controler = $request->input('controler_rating', null);
            $rating->clean = $request->input('clean_rating', null);
            $rating->order = $request->input('order_rating', null);
            $rating->message = $request->input('message', null);
            $rating->phone = $request->input('phone', null);
            $rating->save();
        }
        return redirect()->away('https://etus22.dz');
    }
    public function ctechnique_in(Request $request)
    {
        $clienttypes = ctechniqueclienttypes::all();
        return view('ctechnique.ctechnique_in', compact(['clienttypes']));
    }
    public function add_ctechnique_in(Request $request)
    {
        $request->validate([
            'date' => 'required',
        ]);
        $combined = array_map(function ($type, $name, $immatriculation, $phone) {
            return [
                'type' => $type,
                'name' => $name,
                'immatriculation' => $immatriculation,
                'phone' => $phone,
            ];
        }, $request['type'], $request['name'], $request['immatriculation'], $request['phone']);
        $cnt = 0;
        foreach ($combined as $item) {
            $phoneExists = null;
            if($item['phone']){
                $phoneExists = ctechnique_clients::where('phone', $item['phone'])->exists();
            }
            if (!$phoneExists) {
                if ($item['type'] && $item['name'] && $item['immatriculation']) { //&& $item['phone']){
                    ctechnique_clients::create([
                        'date_controle' => $request['date'],
                        'name' => $item['name'],
                        'type_id' => $item['type'],
                        'immatriculation' => $item['immatriculation'],
                        'phone' => !empty($item['phone']) ? $item['phone'] : null,
                        'last_remind' => $request['date'],
                    ]);
                    $cnt++;
                }
            }
        }
        if ($cnt > 0) {
            return redirect()->back()->with('success', $cnt . ' Clients ajoutés avec succès.');
        } else {
            return redirect()->back()->with('error', 'Aucun client n\'a été ajouté.');
        }
    }
    public function edit_client($id)
    {
        $client = ctechnique_clients::find($id);
        $clienttypes = ctechniqueclienttypes::all();
        if ($client) {
            return view('ctechnique.edit_client', compact(['client','clienttypes']));
        }
        abort(404);
    }
    public function do_edit_client(Request $request,$id)
    {
        $client = ctechnique_clients::find($id);
        if($client){
            $client->type_id=$request->type;   
            $client->phone=$request->phone;   
            $client->immatriculation=$request->immatriculation;   
            $client->update();
            return to_route('app.ctechnique.ctechnique_clients')->with('success', 'Client modifié avec succès!');
        }
        return to_route('app.ctechnique.ctechnique_clients')->with('error', 'Erreur');
    }
    public function deleteclient($id)
    {
        $record = ctechnique_clients::find($id);
        if ($record) {
            $record->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
    public function ctechnique_clients(Request $request)
    {
        $clients = ctechnique_clients::all();
        return view('ctechnique.ctechnique_clients', compact(['clients']));
    }
    public function evaluations(Request $request)
    {
        $ratings = ctechnique_rating::all();
        $sbien = 0;
        $smoyen = 0;
        $smauvais = 0;
        $cbien = 0;
        $cmoyen = 0;
        $cmauvais = 0;
        $clbien = 0;
        $clmoyen = 0;
        $clmauvais = 0;
        $obien = 0;
        $omoyen = 0;
        $omauvais = 0;
        foreach ($ratings as $rating) {
            if ($rating->service == 'bien') {
                $sbien++;
            }
            if ($rating->controler == 'bien') {
                $cbien++;
            }
            if ($rating->clean == 'bien') {
                $clbien++;
            }
            if ($rating->order == 'bien') {
                $obien++;
            }
            if ($rating->service == 'moyen') {
                $smoyen++;
            }
            if ($rating->controler == 'moyen') {
                $cmoyen++;
            }
            if ($rating->clean == 'moyen') {
                $clmoyen++;
            }
            if ($rating->order == 'moyen') {
                $omoyen++;
            }
            if ($rating->service == 'mauvais') {
                $smauvais++;
            }
            if ($rating->controler == 'mauvais') {
                $cmauvais++;
            }
            if ($rating->clean == 'mauvais') {
                $clmauvais++;
            }
            if ($rating->order == 'mauvais') {
                $omauvais++;
            }
        }
        return view('ctechnique.evaluations', compact(['ratings', 'sbien', 'smoyen', 'smauvais', 'cbien', 'cmoyen', 'cmauvais', 'clbien', 'clmoyen', 'clmauvais', 'obien', 'omoyen', 'omauvais']));
    }
    public function marquercommelue(Request $request)
    {
        $request->validate([
            'rating_id' => 'required|exists:ctechnique_ratings,id',
        ]);
        $rating = ctechnique_rating::find($request->rating_id);
        $rating->read = true;
        $rating->update();
        return redirect()->back()->with('success', 'marqué comme lue avec succes!.');
    }
    public function print_evaluation(Request $request)
    {
        $request->validate([
            'rating_id' => 'required|exists:ctechnique_ratings,id',
        ]);
        $rating = ctechnique_rating::find($request->rating_id);
        $html = view('ctechnique.evaluationpdf', compact('rating'))->render();

        $mpdf = new Mpdf([
            'format' => 'A4',
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
        $nomfichier = 'Evaluation' . $rating->id   . '.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function evaluations_pdf(Request $request)
    {
        $request->validate([
            'datedu' => 'required|date',
            'dateau' => 'required|date|after_or_equal:dateduexcel',
        ]);
        $du = $request->datedu;
        $au =  $request->dateau;
        $datedu = Carbon::parse($request->datedu)->startOfDay()->toDateTimeString();
        $dateau =  Carbon::parse($request->dateau)->endOfDay()->toDateTimeString();
        $ratings = ctechnique_rating::whereBetween('created_at', [$datedu, $dateau])->get();
        // dd([$datedu, $dateau],$ratings);
        $html = view('ctechnique.evaluations_pdf', compact('ratings', 'du', 'au'))->render();

        $mpdf = new Mpdf([
            'format' => 'A4',
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
        $nomfichier = 'Evaluation du ' . $du . ' au ' . $au . '.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
    public function etatevaluations_pdf(Request $request)
    {
        $request->validate([
            'datedu' => 'required|date',
            'dateau' => 'required|date|after_or_equal:dateduexcel',
        ]);
        $du = $request->datedu;
        $au =  $request->dateau;
        $datedu = Carbon::parse($request->datedu)->startOfDay()->toDateTimeString();
        $dateau =  Carbon::parse($request->dateau)->endOfDay()->toDateTimeString();
        $ratings = ctechnique_rating::whereBetween('created_at', [$datedu, $dateau])->get();
        $sbien = 0;
        $smoyen = 0;
        $smauvais = 0;
        $cbien = 0;
        $cmoyen = 0;
        $cmauvais = 0;
        $clbien = 0;
        $clmoyen = 0;
        $clmauvais = 0;
        $obien = 0;
        $omoyen = 0;
        $omauvais = 0;
        foreach ($ratings as $rating) {
            if ($rating->service == 'bien') {
                $sbien++;
            }
            if ($rating->controler == 'bien') {
                $cbien++;
            }
            if ($rating->clean == 'bien') {
                $clbien++;
            }
            if ($rating->order == 'bien') {
                $obien++;
            }
            if ($rating->service == 'moyen') {
                $smoyen++;
            }
            if ($rating->controler == 'moyen') {
                $cmoyen++;
            }
            if ($rating->clean == 'moyen') {
                $clmoyen++;
            }
            if ($rating->order == 'moyen') {
                $omoyen++;
            }
            if ($rating->service == 'mauvais') {
                $smauvais++;
            }
            if ($rating->controler == 'mauvais') {
                $cmauvais++;
            }
            if ($rating->clean == 'mauvais') {
                $clmauvais++;
            }
            if ($rating->order == 'mauvais') {
                $omauvais++;
            }
        }
        // dd([$datedu, $dateau],$ratings);
        $html = view('ctechnique.etat_evaluation_pdf', compact(['ratings', 'du', 'au', 'sbien', 'smoyen', 'smauvais', 'cbien', 'cmoyen', 'cmauvais', 'clbien', 'clmoyen', 'clmauvais', 'obien', 'omoyen', 'omauvais']))->render();

        $mpdf = new Mpdf([
            'format' => 'A4-L',
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
        $nomfichier = 'Evaluation du ' . $du . ' au ' . $au . '.pdf';

        $mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->WriteHTML($html);
        return response()->make($mpdf->Output($nomfichier, 'D'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomfichier . '"',
        ]);
    }
}
