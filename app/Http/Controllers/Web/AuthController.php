<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginUi(){
        return view('web.login');
    }
    public function registerUi(){
        return view('web.register');
    }

    public function login(Request $request){
        $email=$request->email;
        $password=$request->password;

        $user=User::where('email',$email)->first();
        if(!$user){
            return back()->with('error','يرجى التأكد من البيانات المدخلة');
        }
        if(!\Hash::check($password,$user->password)){
            return back()->with('error','يرجى التأكد من البيانات المدخلة');
        }
        auth()->login($user);
        return redirect('/');
    }
}
