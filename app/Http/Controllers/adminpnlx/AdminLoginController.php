<?php

namespace App\Http\Controllers\adminpnlx;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session as FacadesSession;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AdminLoginController extends Controller
{



    public function search(Request $request)
    {
        $query = $request->get('query');

        $results = Product::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->get();

        return response()->json($results);
    }

    public function showloginpage()
    {

        return view('auth.adminlogin');
    }

    public function adminlogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please fill in all the required fields');
        }

        $userdata = array(
            'email' => $request->email,
            'password' => $request->password,
        );

        $remember    = $request->has('remember');
        if (Auth::guard('admin')->attempt($userdata)) {

            $cookie = Cookie::forever('admincookie', 'true', 15 * 5 * 6);
            return redirect()->route('dashboard')->withCookie($cookie);
        } elseif (Auth::guard('subadmins')->attempt($userdata)) {
            dd(123);

            return redirect()->route('dashboard');
        }

        return redirect()->back()->with('error', 'Your login failed');
    }
    public function adminlogout()
    {
        Auth::guard('admin')->logout();
        Auth::guard('subadmins')->logout();
        $cookie  = Cookie::forget('admincookie');

        return redirect()->route('showloginpage')->withCookie($cookie);
    }
}
