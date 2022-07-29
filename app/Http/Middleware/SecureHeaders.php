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
        $response->header("Cross-Origin-Embedder-Policy", "require-corp");
        $response->header("Cross-Origin-Opener-Policy", "same-origin");
        $response->header("Cross-Origin-Resource-Policy", "same-origin");
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
            "default-src 'self'; script-src 'self' "
        );

        return $response;
        return $next($request);
    }
}
