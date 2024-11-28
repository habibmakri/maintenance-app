<?php

namespace App\Http\Controllers;

use App\Http\Requests\connectionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Authcontroller extends Controller
{
    public function login()
    {
        // $user = User::create([
        //     'username' => 'habibo',
        //     'firstname' => 'makri',
        //     'lastname' => 'makri',
        //     'email' => 'makri@makri.com',
        //     'password' => Hash::make('1234'), 
        //     'address' => '232 sba',
        //     'city' => 'sba',
        //     'country' => 'sba',
        //     'postal' => 'sba',
        //     'about' => 'Just a test user.',
        // ]);        
        if (Auth::check()) {
            return to_route('app.main');
        }
        $user = new User();
        return view("login", [
            'user' => $user,
        ]);
    }
    public function dologin(connectionRequest $request)
    {
        $credentials = $request->validated();
        // if (Auth::attempt($credentials)) {
        //     $autorisationsArray = explode(',', Auth::user()->autorisations);
        //     echo "hello";
        //     dd($autorisationsArray);
        // } else {
        //     dd($request);
        // }
        // // return redirect('hkj,hkjh');
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->intended(route('app.main'));
        }
        return to_route('login')->withErrors([
            'email'=> 'Informations invalide'
        ])->onlyInput('email');
    }
    public function logout(){
        Auth::logout();
        return to_route(('login'));
    }
}
