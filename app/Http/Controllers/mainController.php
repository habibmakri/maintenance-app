<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class mainController extends Controller
{
    public function main(){ 
        if (!Auth::check()) {
            abort(404);
        }
        $user = Auth::user();
        if ($user != null){
             $name =$user->firstname.' '.$user->lastname;
             $permissions = explode(',', $user->autorisations);;
        }else{
            $name ='???';
            $permissions = [];
        }
        return view("main",compact('name','permissions'));
    }
}
