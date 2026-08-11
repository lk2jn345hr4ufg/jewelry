<?php

namespace App\Support;

/**
 * Store URL normalisation, kept in one place so the importer, the matcher and
 * the API response can never drift apart.
 */
class StoreUrl
{
    /**
     * Lower-cased host without a leading "www.": "https://WWW.Example.com/x" → "example.com".
     *
     * Matching on the host alone means http/https, trailing paths and tracking
     * parameters all resolve to the same store.
     */
    public static function host(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        if (! preg_match('~^[a-z][a-z0-9+.-]*://~i', $url)) {
            $url = 'https://'.$url;
        }

        $host = parse_url($url, PHP_URL_HOST);

        if (! $host) {
            return null;
        }

        $host = preg_replace('/^www\./i', '', mb_strtolower($host));

        // A bare "example" is a typo, not a domain.
        return str_contains($host, '.') ? $host : null;
    }

    /** Canonical store URL as the API reports it: "https://example.com/". */
    public static function canonical(?string $url): ?string
    {
        $host = self::host($url);

        return $host ? 'https://'.$host.'/' : null;
    }
}
