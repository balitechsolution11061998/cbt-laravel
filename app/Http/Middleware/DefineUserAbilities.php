<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class DefineUserAbilities
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {

            foreach (Auth::user()->allPermissions() as $permission) {
                Gate::define($permission->name, function ($user) {
                    return true;
                });
            }
        }

        return $next($request);
    }
}
