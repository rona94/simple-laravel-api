<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\User;

class EnsureTokenIsValid
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
        $token = $request->bearerToken();

        if (isset($token)) {
            $parseJWTToken = parseJWT($token);
            $user = User::getUserByEmail($parseJWTToken->email);

            if($user && Carbon::parse($parseJWTToken->exp) >= Carbon::now()) {
                return $next($request);
            }
        }

        return redirect()->route('unauthorize'); 
    }
}
