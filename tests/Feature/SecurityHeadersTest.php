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

        // Prevent browser caching of application responses.
        $response->headers->set(
            'Cache-Control',
            'no-store, no-cache, must-revalidate, private, max-age=0'
        );

        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

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

        // Disable unnecessary browser capabilities.
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=()'
        );

        // Enable HSTS only in production over HTTPS.
        if (
            app()->environment('production') &&
            $request->isSecure()
        ) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000'
            );
        }

        return $response;
    }
}
