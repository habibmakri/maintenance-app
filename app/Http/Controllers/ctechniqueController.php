<?php

namespace App\Http\Controllers;

use App\Models\ctechnique_clients;
use App\Models\ctechnique_rating;
use App\Models\ctechniqueclienttypes;
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
        return view('ctechnique.ctechnique_in',compact(['clienttypes']));
    }
    public function add_ctechnique_in(Request $request)
    {
        $request->validate([
            'date' => 'required',
        ]);
        $combined = array_map(function($type, $name, $immatriculation, $phone) {
            return [
                'type' => $type,
                'name' => $name,
                'immatriculation' => $immatriculation,
                'phone' => $phone,
            ];
        }, $request['type'], $request['name'], $request['immatriculation'], $request['phone']);
        $cnt = 0;
        foreach($combined as $item){
            $phoneExists = ctechnique_clients::where('phone', $item['phone'])->exists();
            if (!$phoneExists) {
                if($item['type']&& $item['name']&& $item['immatriculation']&& $item['phone']){
                    ctechnique_clients::create([
                        'date_controle' => $request['date'],
                        'name' => $item['name'],
                        'type_id' => $item['type'],
                        'immatriculation' => $item['immatriculation'],
                        'phone' => $item['phone'],
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
        return view('ctechnique.ctechnique_clients',compact(['clients']));
    }
    public function evaluations(Request $request)
    {
        $ratings = ctechnique_rating::all();
        return view('ctechnique.evaluations', compact(['ratings']));
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
}
