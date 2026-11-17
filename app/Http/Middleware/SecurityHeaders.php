<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent MIME-type sniffing.
        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff'
        );

        // Prevent unauthorized external framing.
        $response->headers->set(
            'X-Frame-Options',
            'SAMEORIGIN'
        );

        // Control referrer information.
        $response->headers->set(
            'Referrer-Policy',
            'strict-origin-when-cross-origin'
        );

        // Disable browser capabilities not required by this application.
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=()'
        );

        // Enable HSTS only in production over HTTPS.
        if (app()->environment('production') && $request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000'
            );
        }

        return $response;
    }
}
