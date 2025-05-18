<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is admin based on role name
        if (auth()->check() && auth()->user()->role && auth()->user()->role->name === 'admin') {
            return $next($request);
        }
        
        // Redirect to dashboard with error message instead of just aborting
        return redirect()->route('dashboard')
            ->with('error', 'Deze pagina is alleen toegankelijk voor beheerders.');
    }
}
