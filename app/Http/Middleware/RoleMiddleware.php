<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check either the role column or Spatie roles
        $user = $request->user();
        $hasRole = $user->role === $role || 
                  (method_exists($user, 'hasRole') && $user->hasRole($role));
                  
        if (!$hasRole) {
            // Redirect them to the home page or show a 403 error
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
