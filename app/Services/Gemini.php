<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Gemini
{
    /**
     * Sends a prompt to the Google AI Studio (Gemini) API and returns the text reply.
     *
     * @throws \RuntimeException on missing key or API failure
     */
    public static function generate(string $prompt, float $temperature = 0.8): string
    {
        $key = config('services.gemini.key');
        $model = config('services.gemini.model', 'gemini-2.5-flash');

        if (blank($key)) {
            throw new \RuntimeException('GEMINI_API_KEY is not set in .env');
        }

        $response = Http::timeout(60)
            ->withHeaders(['x-goog-api-key' => $key])
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]],
                ],
                'generationConfig' => [
                    'temperature' => $temperature,
                ],
            ]);

        if ($response->failed()) {
            $message = $response->json('error.message') ?? ('HTTP '.$response->status());
            throw new \RuntimeException("Gemini API error: {$message}");
        }

        $text = $response->json('candidates.0.content.parts.0.text');

        if (blank($text)) {
            throw new \RuntimeException('Gemini returned an empty response (possibly blocked by safety settings).');
        }

        return trim($text);
    }
}
