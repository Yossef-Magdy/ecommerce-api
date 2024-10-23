<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertToIntegerArray
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('categories')) {
            $request->request->set('categories', json_decode($request->categories));
        }
        if ($request->has('subcategories')) {
            $request->request->set('subcategories', json_decode($request->subcategories));
        }
        return $next($request);
    }
}
