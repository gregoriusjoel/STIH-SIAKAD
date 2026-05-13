<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Apply baseline security response headers to all web responses.
 *
 * - X-Frame-Options:        Prevents clickjacking by disallowing the page to be
 *                           embedded in iframes from other origins.
 * - X-Content-Type-Options: Prevents MIME-sniffing attacks.
 * - Referrer-Policy:        Limits Referer leakage to cross-origin destinations.
 * - Strict-Transport-Security:
 *                           Instructs browsers to use HTTPS for the configured
 *                           lifetime (only emitted over HTTPS in production).
 * - Permissions-Policy:     Disables unused powerful browser features.
 *
 * Note: Content-Security-Policy is intentionally NOT enforced here because the
 * application currently relies on inline scripts/styles and external CDNs.
 * Add a CSP header here later once those have been audited or nonce-ified.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $headers = $response->headers;

        if (! $headers->has('X-Frame-Options')) {
            $headers->set('X-Frame-Options', 'SAMEORIGIN');
        }

        if (! $headers->has('X-Content-Type-Options')) {
            $headers->set('X-Content-Type-Options', 'nosniff');
        }

        if (! $headers->has('Referrer-Policy')) {
            $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        }

        if (! $headers->has('Permissions-Policy')) {
            $headers->set(
                'Permissions-Policy',
                'camera=(), microphone=(), geolocation=(), payment=(), usb=()'
            );
        }

        // Only emit HSTS over actual HTTPS connections, otherwise browsers
        // will ignore it and curl-style tooling may flag it as misconfigured.
        if ($request->isSecure() && ! $headers->has('Strict-Transport-Security')) {
            $headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        // Hide PHP version disclosure. The Symfony header bag does not know
        // about the X-Powered-By header injected by PHP itself (controlled by
        // the `expose_php` ini directive), so we must strip it at the PHP
        // output layer as well.
        $headers->remove('X-Powered-By');
        if (function_exists('header_remove')) {
            @header_remove('X-Powered-By');
        }

        return $response;
    }
}
