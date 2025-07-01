<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTeacherMiddleware
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
            return redirect()->route('login');
        }
        
        $user = $request->user();
        
        // Check if user is admin or teacher
        $isAdminOrTeacher = 
            $user->role === 'admin' || 
            $user->role === 'teacher' || 
            (method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('teacher')));
            
        if (!$isAdminOrTeacher) {
            // Redirect to dashboard with error message
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this section.');
        }

        return $next($request);
    }
}
