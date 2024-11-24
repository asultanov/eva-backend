<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->headers->get('content-type');
        if ($host != 'application/json' && !env('APP_DEBUG',true))
            return response()->json(['message' => 'Forbidden'], 403);
        return $next($request);
    }
}
