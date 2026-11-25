<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInstructor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isInstructor()) {
            abort(403, 'Akses terbatas untuk Instruktur dan Administrator.');
        }

        return $next($request);
    }
}

