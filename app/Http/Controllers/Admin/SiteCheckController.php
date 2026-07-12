<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SiteCheckController extends Controller
{
    protected const BATCH = 15;
    protected const DEAD = [0, 404, 410];

    public function index()
    {
        $withSite = Business::whereNotNull('website')->where('website', '!=', '');

        return view('admin.sitecheck.index', [
            'total' => (clone $withSite)->count(),
            'checked' => (clone $withSite)->whereNotNull('website_checked_at')->count(),
            'deadCount' => (clone $withSite)->whereIn('website_status', self::DEAD)->count(),
            'errorCount' => (clone $withSite)->where('website_status', '>=', 400)
                ->whereNotIn('website_status', self::DEAD)->count(),
            'problems' => Business::with(['city', 'category'])
                ->whereNotNull('website_checked_at')
                ->where(fn ($q) => $q->where('website_status', '>=', 400)->orWhere('website_status', 0))
                ->orderByRaw('website_status in (0, 404, 410) desc')
                ->orderBy('website_status')
                ->paginate(30),
        ]);
    }

    /** Checks the next batch of unchecked websites; called in a loop from the UI. */
    public function run(Request $request)
    {
        set_time_limit(180);

        $businesses = Business::whereNotNull('website')->where('website', '!=', '')
            ->whereNull('website_checked_at')
            ->orderBy('id')
            ->take(self::BATCH)
            ->get();

        if ($businesses->isEmpty()) {
            return response()->json(['done' => true, 'checked' => 0, 'remaining' => 0, 'found' => 0]);
        }

        // Parallel HEAD requests
        $responses = Http::pool(fn ($pool) => $businesses->map(
            fn ($b) => $pool->as((string) $b->id)
                ->timeout(8)
                ->connectTimeout(5)
                ->withOptions(['allow_redirects' => ['max' => 5]])
                ->withUserAgent('Mozilla/5.0 (compatible; GleamionLinkCheck/1.0)')
                ->head($b->website)
        )->all());

        $found = 0;
        foreach ($businesses as $business) {
            $response = $responses[(string) $business->id] ?? null;

            $status = ($response instanceof \Illuminate\Http\Client\Response) ? $response->status() : 0;

            // Some servers reject HEAD — retry those few with GET before judging.
            if (in_array($status, [403, 405, 501])) {
                try {
                    $status = Http::timeout(8)->connectTimeout(5)
                        ->withOptions(['allow_redirects' => ['max' => 5], 'stream' => true])
                        ->withUserAgent('Mozilla/5.0 (compatible; GleamionLinkCheck/1.0)')
                        ->get($business->website)->status();
                } catch (ConnectionException|\Throwable $e) {
                    $status = 0;
                }
            }

            $business->forceFill([
                'website_status' => $status,
                'website_checked_at' => now(),
            ])->save();

            if ($status >= 400 || $status === 0) {
                $found++;
            }
        }

        $remaining = Business::whereNotNull('website')->where('website', '!=', '')
            ->whereNull('website_checked_at')->count();

        return response()->json([
            'done' => $remaining === 0,
            'checked' => $businesses->count(),
            'remaining' => $remaining,
            'found' => $found,
        ]);
    }

    /** Clears all results so a full re-check can run. */
    public function reset()
    {
        Business::whereNotNull('website_checked_at')
            ->update(['website_status' => null, 'website_checked_at' => null]);

        return back()->with('ok', 'Check results cleared — run the check again for fresh results.');
    }

    /** Hides one business from the public site. */
    public function hide(Business $business)
    {
        $business->update(['is_active' => false]);

        return back()->with('ok', "\"{$business->name}\" is now hidden from the public site.");
    }

    /** Hides every business whose site is dead (connection failed, 404 or 410). */
    public function hideDead()
    {
        $count = Business::whereIn('website_status', self::DEAD)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        return back()->with('ok', "{$count} businesses with dead websites were hidden.");
    }
}
