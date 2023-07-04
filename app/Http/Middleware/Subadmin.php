<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Subadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {


        if (Auth::guard('subadmins')->user()->user_role_id == 3) {
            return $next($request);
        }


        // if (Auth::guard('subadmins')->user()->user_role_id == 3) {

        return $next($request);
        // }
        // return redirect()->route('login');

        // if (Auth::check()) {

        //     if (Auth::guard('subadmins')->user()->user_role_id == 3) {

        //     } else {
        //         return redirect()->route('home');
        //     }
        // } else {

        //     return redirect()->route('login');
        // }
    }
}
