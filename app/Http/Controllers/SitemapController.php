<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /** Sitemap index — lists the individual sitemaps. */
    public function index(): Response
    {
        $maps = [
            ['loc' => route('sitemap.pages')],
            ['loc' => route('sitemap.tools')],
            ['loc' => route('sitemap.categories')],
            ['loc' => route('sitemap.cities')],
            ['loc' => route('sitemap.businesses')],
        ];

        return $this->xml(view('sitemap-index', ['maps' => $maps])->render());
    }

    /** Static, top-level pages. */
    public function pages(): Response
    {
        $urls = [
            ['loc' => route('home'), 'priority' => '1.0', 'freq' => 'daily'],
            ['loc' => route('coupons.index'), 'priority' => '0.8', 'freq' => 'daily'],
            ['loc' => route('search'), 'priority' => '0.5', 'freq' => 'weekly'],
        ];

        return $this->render($urls);
    }

    /** Every jewelry tool, plus the tools index. */
    public function tools(): Response
    {
        $urls = [[
            'loc' => route('tools.index'),
            'priority' => '0.9',
            'freq' => 'weekly',
        ]];

        foreach (array_keys(ToolController::TOOLS) as $slug) {
            $urls[] = [
                'loc' => route('tools.show', $slug),
                'priority' => '0.8',
                'freq' => 'monthly',
            ];
        }

        return $this->render($urls);
    }

    /** Categories that have at least one active business. */
    public function categories(): Response
    {
        $urls = [];

        foreach (Category::whereHas('businesses', fn ($q) => $q->where('is_active', true))->get() as $category) {
            $urls[] = ['loc' => route('category.show', $category), 'priority' => '0.7', 'freq' => 'weekly'];
        }

        return $this->render($urls);
    }

    /** Cities that have at least one active business, plus their category pages. */
    public function cities(): Response
    {
        $urls = [];

        $cities = City::whereHas('businesses', fn ($q) => $q->where('is_active', true))
            ->with(['categories'])
            ->get();

        foreach ($cities as $city) {
            $urls[] = ['loc' => route('city.show', $city), 'priority' => '0.7', 'freq' => 'weekly'];
        }

        return $this->render($urls);
    }

    /** Active business profiles — the largest set. */
    public function businesses(): Response
    {
        $urls = [];

        Business::where('is_active', true)
            ->select('slug', 'updated_at')
            ->orderBy('id')
            ->chunk(1000, function ($businesses) use (&$urls) {
                foreach ($businesses as $business) {
                    $urls[] = [
                        'loc' => route('business.show', $business),
                        'priority' => '0.8',
                        'freq' => 'weekly',
                        'lastmod' => optional($business->updated_at)->toAtomString(),
                    ];
                }
            });

        return $this->render($urls);
    }

    protected function render(array $urls): Response
    {
        return $this->xml(view('sitemap', ['urls' => $urls])->render());
    }

    protected function xml(string $body): Response
    {
        return response($body, 200)->header('Content-Type', 'application/xml');
    }
}
