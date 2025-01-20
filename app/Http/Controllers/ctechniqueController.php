<?php

namespace App\Http\Controllers;

use App\Models\ctechnique_rating;
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
