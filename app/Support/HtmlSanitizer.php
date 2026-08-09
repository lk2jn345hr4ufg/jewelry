<?php

namespace App\Support;

/**
 * Strips rich text down to a small allowlist of formatting tags.
 *
 * Coupon descriptions are rendered unescaped, so everything reaching that
 * field — the admin editor as well as the offers API — must pass through here.
 */
class HtmlSanitizer
{
    public static function clean(?string $html): ?string
    {
        if (blank($html)) {
            return null;
        }

        $clean = strip_tags($html, '<p><br><strong><em><u><b><i><ul><ol><li><a>');

        // Keep only href on <a>, and only links we consider safe.
        $clean = preg_replace_callback('/<a\b[^>]*>/i', function ($m) {
            if (preg_match('/href\s*=\s*"([^"]*)"/i', $m[0], $h)) {
                $url = $h[1];
                if (preg_match('~^(https?:)?//~i', $url) || str_starts_with($url, '/')) {
                    return '<a href="'.htmlspecialchars($url, ENT_QUOTES).'" rel="nofollow noopener" target="_blank">';
                }
            }

            return '<a>';
        }, $clean);

        // Drop attributes from every other allowed tag.
        $clean = preg_replace('/<(p|br|strong|em|u|b|i|ul|ol|li)\b[^>]*>/i', '<$1>', $clean);

        return trim($clean) === '' ? null : $clean;
    }
}
