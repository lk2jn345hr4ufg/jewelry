<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Services\Gemini;
use Illuminate\Http\Request;

class AiContentController extends Controller
{
    public const DEFAULT_PROMPT = <<<'PROMPT'
Write a unique, natural-sounding directory description (70-110 words) for this jewelry business. Plain text only, no markdown, no headings, no quotes around the text. Mention what the business does and the city it serves. Do not invent specific facts like founding years, awards, or family history. Do not use the words "nestled", "hidden gem" or "look no further".

Business name: {name}
Category: {category}
City: {city}
Existing description (may be empty or low quality): {about}
PROMPT;

    public function index()
    {
        return view('admin.ai.rewrite', [
            'keySet' => filled(config('services.gemini.key')),
            'model' => config('services.gemini.model'),
            'defaultPrompt' => self::DEFAULT_PROMPT,
            'counts' => [
                'all' => Business::count(),
                'empty' => Business::where(fn ($q) => $q->whereNull('about')->orWhere('about', ''))->count(),
                'short' => Business::whereRaw('CHAR_LENGTH(COALESCE(about, "")) < 200')->count(),
            ],
        ]);
    }

    /** Rewrites one batch of descriptions; the UI calls this in a loop. */
    public function run(Request $request)
    {
        $data = $request->validate([
            'prompt' => ['required', 'string', 'max:5000'],
            'mode' => ['required', 'in:empty,short,all'],
            'batch' => ['required', 'integer', 'between:1,10'],
            'last_id' => ['nullable', 'integer', 'min:0'],
        ]);

        set_time_limit(300);

        $query = Business::query()->orderBy('id')->where('id', '>', (int) ($data['last_id'] ?? 0));

        if ($data['mode'] === 'empty') {
            $query->where(fn ($q) => $q->whereNull('about')->orWhere('about', ''));
        } elseif ($data['mode'] === 'short') {
            $query->whereRaw('CHAR_LENGTH(COALESCE(about, "")) < 200');
        }

        $businesses = $query->with(['city', 'category'])->take((int) $data['batch'])->get();

        if ($businesses->isEmpty()) {
            return response()->json(['done' => true, 'items' => [], 'last_id' => $data['last_id'] ?? 0]);
        }

        $items = [];
        $lastId = (int) ($data['last_id'] ?? 0);

        foreach ($businesses as $business) {
            $lastId = $business->id;

            $prompt = strtr($data['prompt'], [
                '{name}' => $business->name,
                '{category}' => $business->category?->name ?? 'Jewelry business',
                '{city}' => $business->city?->full_name ?? '',
                '{about}' => (string) $business->about,
            ]);

            try {
                $text = Gemini::generate($prompt);
                $text = trim(strip_tags($text), " \t\n\r\"'`");

                if (mb_strlen($text) < 30) {
                    throw new \RuntimeException('Response too short — not saved.');
                }

                $business->update(['about' => $text]);
                $items[] = ['id' => $business->id, 'name' => $business->name, 'ok' => true];
            } catch (\Throwable $e) {
                $items[] = ['id' => $business->id, 'name' => $business->name, 'ok' => false, 'error' => $e->getMessage()];

                // Config / auth errors will fail for every row — stop the loop early.
                if (str_contains($e->getMessage(), 'GEMINI_API_KEY') || str_contains($e->getMessage(), 'API key')) {
                    return response()->json(['done' => true, 'items' => $items, 'last_id' => $lastId, 'fatal' => $e->getMessage()]);
                }
            }
        }

        return response()->json(['done' => false, 'items' => $items, 'last_id' => $lastId]);
    }
}
