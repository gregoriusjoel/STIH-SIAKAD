<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Override cookie XSRF-TOKEN agar `HttpOnly = true`.
 *
 * Default Laravel set XSRF-TOKEN dengan `HttpOnly = false` agar Axios bisa
 * baca tokennya dari cookie. Tapi itu membuat token rentan terhadap pencurian
 * via XSS. Strategi baru kami:
 *
 *   - Cookie XSRF-TOKEN tetap dikirim (dipakai server untuk verifikasi),
 *     tapi sekarang HttpOnly + Secure + SameSite=Lax agar JS tidak bisa baca.
 *   - JavaScript mengambil token dari meta tag <meta name="csrf-token" ...>
 *     yang sudah ada di setiap layout, lalu set header X-CSRF-TOKEN per-request
 *     (lihat resources/js/bootstrap.js).
 *
 * Middleware ini di-append ke grup `web` (lihat bootstrap/app.php).
 * Penempatan setelah VerifyCsrfToken penting: kita override cookie hasil
 * `addCookieToResponse()` milik VerifyCsrfToken.
 */
class SecureCsrfCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        foreach ($response->headers->getCookies() as $cookie) {
            if ($cookie->getName() !== 'XSRF-TOKEN') {
                continue;
            }

            // Pakai SameSite dari config/session agar konsisten dengan cookie session;
            // bisa di-override via .env (SESSION_SAME_SITE).
            $sameSite = config('session.same_site', 'lax');

            // `secure` ikut konfigurasi session: true di production HTTPS,
            // false di lokal HTTP supaya cookie tetap terkirim saat dev.
            $secure = (bool) config('session.secure', $request->isSecure());

            $response->headers->removeCookie(
                $cookie->getName(),
                $cookie->getPath(),
                $cookie->getDomain()
            );

            $response->headers->setCookie(new Cookie(
                $cookie->getName(),
                $cookie->getValue(),
                $cookie->getExpiresTime(),
                $cookie->getPath(),
                $cookie->getDomain(),
                $secure,
                true,           // httpOnly — JS tidak bisa baca via document.cookie
                $cookie->isRaw(),
                $sameSite
            ));
        }

        return $response;
    }
}
