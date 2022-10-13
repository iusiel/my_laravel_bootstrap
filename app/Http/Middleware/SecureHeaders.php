<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecureHeaders
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
        $response = $next($request);

        $response->header(
            "Strict-Transport-Security",
            "max-age=31536000 ; includeSubDomains"
        );
        $response->header("X-Frame-Options", "sameorigin");
        $response->header("X-Content-Type-Options", "nosniff");
        $response->header("X-Permitted-Cross-Domain-Policies", "none");
        $response->header("Referrer-Policy", "same-origin");
        $response->header("Clear-Site-Data", '"cache","cookies"');
        // $response->header("Cross-Origin-Embedder-Policy", "require-corp");
        // $response->header("Cross-Origin-Opener-Policy", "same-origin");
        // $response->header("Cross-Origin-Resource-Policy", "same-origin");
        $response->header(
            "Cache-Control",
            "private, max-age=604800, must-revalidate"
        );
        $response->header(
            "Feature-Policy",
            "accelerometer 'none'; ambient-light-sensor 'none'; autoplay 'none'; battery 'none'; camera 'none'; display-capture 'none'; document-domain 'none'; encrypted-media 'none'; fullscreen 'none'; geolocation 'none'; gyroscope 'none'; magnetometer 'none'; microphone 'none'; midi 'none'; navigation-override 'none'; payment 'none'; picture-in-picture 'none'; speaker 'none'; usb 'none'; vibrate 'none'; vr 'none'; " //phpcs:ignore
        );
        $response->header(
            "Content-Security-Policy",
            $this->generateCSP()
            // "default-src 'self'; script-src 'self' 127.0.0.1:5173; style-src 'self' 'nonce-" .
            //     config("csp.nonce") .
            //     "' 127.0.0.1:5173;"
        );

        return $response;
    }

    private function generateCSP()
    {
        $csp = [];

        $defaultSrc = ["'self'"];
        $csp[] = "default-src " . implode(" ", $defaultSrc);

        $scriptSrc = [
            "'self'",
            "'strict-dynamic'",
            "'nonce-" . config("csp.nonce") . "'",
            "127.0.0.1:5173",
        ];
        $csp[] = "script-src " . implode(" ", $scriptSrc);

        $styleSrc = ["'self'", "'unsafe-inline'", "127.0.0.1:5173"];
        $csp[] = "style-src " . implode(" ", $styleSrc);

        $coonectSrc = ["'self'", "ws:"];
        $csp[] = "connect-src " . implode(" ", $coonectSrc);

        return implode("; ", $csp);
    }
}
