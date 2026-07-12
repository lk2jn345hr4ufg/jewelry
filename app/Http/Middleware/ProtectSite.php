<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Locks the entire public site behind a shared password when
 * SITE_PASSWORD is set in .env. Leave it empty to keep the site open.
 */
class ProtectSite
{
    public function handle(Request $request, Closure $next): Response
    {
        $password = config('app.site_password');

        if (blank($password)) {
            return $next($request); // protection disabled
        }

        if ($request->session()->get('site_unlocked') === true) {
            return $next($request);
        }

        // The unlock form itself, and logged-in admins, always pass.
        if ($request->routeIs('site.unlock', 'admin.login', 'admin.login.post')) {
            return $next($request);
        }

        if ($request->user()?->is_admin) {
            return $next($request);
        }

        return response()->view('locked', [], 401);
    }
}
