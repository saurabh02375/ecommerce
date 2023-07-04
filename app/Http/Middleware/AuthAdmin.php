<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Model\Admin;
use App\Models\AclAdminAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;

use Redirect;
use Symfony\Component\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;


class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (!empty(Auth::guard('admin')->guest())) {
            return Redirect()->to('/admin');
        } elseif (Auth::guard('admin')->user()->user_role_id == 1) {
            return $next($request);
        }
        if (Auth::guard('subadmins')->user()->user_role_id == 3) {
            dd(123);
            return $next($request);
        }
        //  elseif (Auth::guard('subadmins')->user()->user_role_id == 3) {
        //     dd(123);
        //     return $next($request);
        // }
        return Redirect()->to('/admin');
    }
}
