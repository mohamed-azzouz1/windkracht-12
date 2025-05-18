<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class StudentMiddleware
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
        if (Auth::check() && Auth::user()->role && Auth::user()->role->name === 'student') {
            return $next($request);
        }
        
        return redirect()->route('dashboard')->with('error', 'Deze pagina is alleen toegankelijk voor studenten.');
    }
}
