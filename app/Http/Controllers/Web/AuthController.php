<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginUi(){
        return view('web.login');
    }
    public function registerUi(){
        $cities=City::where('is_main',true)->orderBy('name')->get();
        return view('web.register',compact('cities'));
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

    public function register(Request $request){
        $this->validate($request,[
            'name'=>'required|string|min:3',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8',
            'confiermPassword'=>'same:password',
            'phone'=>'required|min:10',
            'city'=>'required|exists:cities,id',
            'address'=>'required|string',
        ],[
            'name.*'=>'يرجى إدخال اسم صالح',
            'email.*'=>'يرجى إدخال بريد إلكتروني صالح',
            'email.unique'=>'البريد الإلكتروني موجود بالفعل',
            'password.*'=>'يرجى إدخال كلمة مرور من 8 احرف على الأقل',
            'confiermPassword.*'=>'كلمة المرور غير متطابقة',
            'phone.*'=>'يرجى إدخال رقم هاتف صالح مع رمز الدولة',
            'city'=>'يرجى تحديد المدينة',
            'address'=>'يرجى إدخال عنوانك التفصيلي'
        ]);
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'phone'=>$request->phone,
            'city_id'=>$request->city,
            'address'=>$request->address
        ]);
        auth()->login($user);
        return redirect('/');
    }

    public function forgetPasswordUi(){
        return view('web.forget-password');
    }
}
