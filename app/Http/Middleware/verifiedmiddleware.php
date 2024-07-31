<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class verifiedmiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        try {
            if (Auth::user() != null) {

                foreach (Auth::user()->allPermissions() as $permission) {
                    Gate::define($permission->name, function () {
                        return true;
                    });
                }

                View::share('authVariable', Auth::user());

                return $next($request);
            } else {
                return redirect()->route('login');
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
