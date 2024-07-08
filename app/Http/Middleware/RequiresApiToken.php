<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequiresApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasHeader('x-api-key')) {
            $token = $request->header('x-api-key');

            if ($token === env('APP_API_KEY')) {
                return $next($request);
            }
        }

        return response('Unauthorized.', Response::HTTP_UNAUTHORIZED);
    }
}
