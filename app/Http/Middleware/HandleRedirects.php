<?php

namespace App\Http\Middleware;

use App\Models\RedirectRule;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        $path = '/'.ltrim($request->path(), '/');

        // Never redirect the admin panel or health check.
        if (str_starts_with($path, '/admin') || $path === '/up') {
            return $next($request);
        }

        try {
            $rules = RedirectRule::cached();
        } catch (\Throwable $e) {
            return $next($request); // table missing (pre-migration) — do nothing
        }

        foreach ($rules as $rule) {
            if ($rule->is_regex) {
                $pattern = '#^'.str_replace('#', '\#', $rule->from_pattern).'$#i';
                $target = @preg_replace($pattern, $rule->to_pattern, $path, 1, $count);
                if ($target !== null && $count > 0) {
                    return $this->redirectTo($request, $rule, $target);
                }
            } elseif (strcasecmp(rtrim($rule->from_pattern, '/'), rtrim($path, '/')) === 0 && $path !== '/') {
                return $this->redirectTo($request, $rule, $rule->to_pattern);
            } elseif ($rule->from_pattern === '/' && $path === '/') {
                return $this->redirectTo($request, $rule, $rule->to_pattern);
            }
        }

        return $next($request);
    }

    protected function redirectTo(Request $request, $rule, string $target): Response
    {
        RedirectRule::whereKey($rule->id)->increment('hits');

        if ($qs = $request->getQueryString()) {
            $target .= (str_contains($target, '?') ? '&' : '?').$qs;
        }

        return redirect($target, in_array($rule->status_code, [301, 302, 307, 308]) ? $rule->status_code : 301);
    }
}
