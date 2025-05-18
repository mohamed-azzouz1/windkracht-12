<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Instructor;

class InstructorMiddleware
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
        if (Auth::check()) {
            $user = Auth::user();
            $instructor = Instructor::where('user_id', $user->id)->first();
            
            if ($instructor) {
                return $next($request);
            }
        }
        
        return redirect()->route('dashboard')->with('error', 'Deze pagina is alleen toegankelijk voor instructeurs.');
    }
}
