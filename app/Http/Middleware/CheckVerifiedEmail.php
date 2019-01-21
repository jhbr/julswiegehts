<?php

namespace App\Http\Middleware;

use Closure;

class CheckVerifiedEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->user() && !$request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Ihre E-Mail-Adresse wurde noch nicht bestätigt.'
            ], 403);
        }

        return $response;
    }
}
