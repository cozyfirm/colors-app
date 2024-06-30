<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth{
    protected bool $_authenticated = false;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        if(isset($request->api_token)){
            if($user = User::where('api_token', $request->api_token)->first()) $this->_authenticated = true;
        }

        if($this->_authenticated){
            Auth::login($user);
            return $next($request);
        }else{
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            return redirect()->guest(route('public.auth'));
        }
    }
}
