<?php

namespace App\Http\Controllers\fashi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function viewotp($validate_string)
    {
        // dd($validate_string);
        return view('auth.otp', compact('validate_string'));
    }

    public function verifyotp(Request $request, $validate_string)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'otp' => 'required',

            ]);

            $otp    =   User::where('validate_string', $validate_string)->where('verification_code', $request->input('otp'))->first();

            //test

            if (empty($otp)) {
                session()->flash('error', 'Otp is not correct');
                return redirect()->back();
            } else {
                $otp->email_verified_at = date('Y-m-d H:i:s');
                $otp->save();
                return redirect()->route('home', compact('otp'))->with('success', 'your account has been registered');
            }
        }
    }

    public function register()
    {

        return view('user.Auth.register');
    }

    public function store(Request $request)
    {
        $formData = $request->all();
        $validation = $request->validate([
            'name' => ['required'],
            'email' => 'required',
            'password' => 'min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6'
        ]);


        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $code = rand(100000, 123456);
        $user->password = Hash::make($request->input('password'));
        $user->verification_code            =  $code;
        $user->validate_string       = md5($request->input('email') . time() . time());

        $user->save();

        return redirect()->route('viewotp', $user->validate_string);
    }

    public function login()
    {
        if (Auth::user()) {
            return  redirect()->route('home');
        }
        return view('user.Auth.login');
    }

    public function logout()
    {

        Auth::logout();

        Session::flush();

        $cookie = Cookie::forget('remember');
        return Redirect()->back()->withCookie($cookie)->with('success', 'logout success');
    }


    public function postlogin(Request $request)
    {

        $formData = $request->all();

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator) {
            $credentials = [
                'email' => $request['email'],
                'password' => $request['password'],
            ];

            $remember = $request->has('remember');
            if (Auth::attempt($credentials)) {
                if ($remember) {
                    $cookie      = Cookie::forever('remember', 'true',  30 * 24 * 60);
                    // dd($cookie);
                    return redirect()->route('home')->withCookie($cookie)->with('success', 'your login success');
                }

                return redirect()->route('home')->with('success', 'your login success');
            }
        }
        return redirect()->back()->with('error', 'your login failed');
    }
}

// $cookie = Cookie::make('remember_me', 'true', 30 * 24 * 60);


// public function postlogin(Request $request)
// {
//     $formData = $request->all();

//     $validator = Validator::make($request->all(), [
//         'email' => 'required',
//         'password' => 'required',
//     ]);

//     if ($validator->passes()) {
//         $credentials = [
//             'email' => $request['email'],
//             'password' => $request['password'],
//         ];

//         $rememberMe = $request->has('remember');

//         if (Auth::attempt($credentials)) {
//             if ($rememberMe) {
//                 $cookie = Cookie::make('remember_me', 'true', 30 * 24 * 60);
//                 return redirect()->route('home')->withCookie($cookie)->with('success', 'Your login is successful.');
//             }

//             return redirect()->route('home')->with('success', 'Your login is successful.');
//         }
//     }

//     return redirect()->back()->with('error', 'Your login failed.');
// }