<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class TeacherOrAdminMiddleware
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
        if (!Auth::check()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $user = Auth::user();
        
        if ($user->hasRole('admin') || $user->hasRole('teacher')) {
            return $next($request);
        }
        
        throw UnauthorizedException::forRoles(['teacher', 'admin']);
    }
}
