<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\GraphQLExceptionHandler;
use App\Helpers\StrHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Jobs\SendEmailJob;
use App\Mail\ForgetPasswordEmail;
use App\Mail\ResetPasswordForgetEmail;
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginUi()
    {

        return view('theme2.auth');
    }

    public function registerUi()
    {
        return to_route('login.ui')->withFragment('register');
        $cities = City::where('is_main', true)->orderBy('name')->get();
        return view('web.register', compact('cities'));
    }

    public function login(Request $request)
    {
        dd($request->all());
        $this->validate($request, [
            'email'=>'required|email|exists:users,email',
            'password'=>'required|min:8'
        ],[
            'email.required' => 'يرجى إدخال بريدك الإلكتروني',
            'email.email' => 'يرجى إدخال بريدك الإلكتروني',
            'email.exists' => 'لم يتم العثور على بريدك الإلكتروني',
            'password.required' => 'يرجى إدخال كلمة المرور',
            'password.min' => 'كلمة المرور يجب ان لا تقل عن 8 أحرف',
        ]);
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->with('error', 'يرجى التأكد من البيانات المدخلة');
        }
        if (!\Hash::check($password, $user->password)) {
            return back()->with('error', 'يرجى التأكد من البيانات المدخلة');
        }
        auth()->login($user);
        return redirect('/');
    }

    public function register(RegisterRequest $request)
    {
        $this->validate($request, [
            'name'=>'required|min:3',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8',
            'confirmPassword'=>'required|same:password',
            'phone'=>'required|min:8|unique:users,phone',
            'phone_code'=>'required|exists:countries,code',
            'city'=>'required|exists:cities,id',
            'address'=>'required'
            ],[
                'name.required' => 'يرجى إدخال اسمك',
                'name.min' => 'اسمك يجب ان لا يقل عن 3 أحرف',
                'email.required' => 'يرجى إدخال بريدك الإلكتروني',
                'email.email' => 'يرجى إدخال بريدك الإلكتروني',
                'email.unique' => 'البريد الإلكتروني موجود بالفعل',
                'password.required' => 'يرجى إدخال كلمة المر',
                'password.min' => 'كلمة المرور يجب ان لا تقل عن 8 أحرف',
                'confirmPassword.required' => 'يرجى إدخال كلمة المرور',
                'confirmPassword.same' => 'الكلمة غير متطابقة',
                'phone.required' => 'يرجى إدخال رقم الهاتف',
                'phone.min' => 'رقم الهاتف يجب ان لا يقل عن 8 أرقام',
                'phone.unique' => 'رقم الهاتف مُسجل بالفعل',
                'phone_code.required' => 'يرجى إدخال كود',
                'phone_code.exists' => 'يرجى إدخال كود',
                'city.required' => 'يرجى إدخال المدينة',
                'address.required' => 'يرجى إدخال العنوان',
            ]);

        if(\Str::startsWith($request->phone, '0')){
            $request->phone = \Str::substr($request->phone, 1);
        }

        $affiliate_id = User::where('affiliate',$request->affiliate)->first()?->id;
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'phone_code' => $request->phone_code,
            'city_id' => $request->city,
            'address' => $request->address,
            'code_verified' =>StrHelper::generateDigits(6),
            'level' => 'user',
            'is_active' => true,
            'user_id' => $affiliate_id,
            'is_special' => false,
            'seller_name'=>$request->name
        ]);
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('index');
    }

    public function forgetPasswordUi()
    {
        return view('theme2.reset_password');
    }

    public function forgetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'يرجى إدخال بريدك الإلكتروني',
            'email.email' => 'يرجى إدخال بريدك الإلكتروني',
            'email.exists' => 'لم يتم العثور على بريدك في سجلاتنا',
        ]);
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->with('error', 'لم يتم العثور على بريدك في سجلاتنا');
        }
        $code = StrHelper::getResetPassword();
        $user->update(['reset_password' => $code]);
        $job = new SendEmailJob($user, new ForgetPasswordEmail($code));
        dispatch($job);

        return redirect('/')/*->route('change-password.ui',['code'=>$code])*/ ->with('success', 'تم إرسال رسالة إلى بريدك الإلكتروني');
    }

    public function changePasswordUi()
    {
        return view('web.change-password');
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|exists:users,reset_password',
            'password' => 'required|min:8|same:confiemPassword',

        ], [
            'code.*' => 'للأسف الكود الخاص بك غير موجود يرجى طلب تغيير كلمة المرور من جديد',
            'password.min' => 'يرجى غدخال كلمة مرور من 8 أحرف على الاقل',
            'password.same' => 'كلمة المرور غير متطابقة'
        ]);

        $user = User::whereNotNull('reset_password')->where(['reset_password' => $request->code])->first();
        if ($user) {
            $user->update(['password' => bcrypt($request->password), 'reset_password' => null]);
            return redirect()->route('login.ui')->with('success', 'تم تغيير كلمة المرور');
        }
        return back()->with('error', 'إنتهت مدة الرابط يرجى طلب إستعادة كلمة المرور مرة أخرى');
    }

    public function logout(){
        auth()->logout();
        return redirect('/');
    }
}
