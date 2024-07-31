<?php
namespace App\Http\Middleware;

use App\Models\UserAccessLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Proceed with the request
        $response = $next($request);

        // Log user access
        $log = new UserAccessLog();
        if (Auth::check()) {
            $user = Auth::user();
            $log->user_id = $user->id;
            $log->user_email = $user->email;
        }
        $log->url = $request->url();
        $log->method = $request->method();
        $log->ip = $request->ip();
        $log->timestamp = now();
        $log->save();

        return $response;
    }
}
