<?php

namespace App\Http\Middleware;

use Closure;

class SecurityLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        $needsVerification = true;
        if($request->is('signin')
            || $request->is('logout')
            || $request->is('api/*/user/login')
            || $request->is('api/*/user/register')
            || $request->is('api/user*')
            || $request->is('api/job*')
            || $request->session()->get("LoggedIn")){
            $needsVerification = false;
        }

        if($needsVerification){
            $request->message = 'Sign in to continue.';
            return redirect('/signin');
        }
        return $next($request);
    }
}
