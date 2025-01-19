<?php

namespace App\Http\Controllers;

use App\Models\ctechnique_rating;
use Illuminate\Http\Request;

class ctechniqueController extends Controller
{
    
    public function rate_ctechnique(Request $request){
        return view('ctechnique.rate_us');
    }
    public function add_rate_ctechnique(Request $request){
        if ($request->filled(['service_rating']) ||$request->filled(['controler_rating']) ||$request->filled(['clean_rating']) ||$request->filled(['order_rating']) ||$request->filled(['message']) ||$request->filled(['phone'])) {
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
}
