<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleResponseTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        $endTime = microtime(true);

        $responseTime = round(($endTime - $startTime) * 1000, 2);

        $response->headers->set('x-response-time', $responseTime . 'ms');

        return $response;
    }
}
