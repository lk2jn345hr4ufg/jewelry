<?php

namespace App\Http\Controllers;

class ToolController extends Controller
{
    /** Registry of tools: slug => [title, blurb, view]. */
    public const TOOLS = [
        'ring-size-converter' => [
            'title' => 'Ring Size Converter',
            'blurb' => 'Convert ring sizes between US, UK, EU and Japan — or measure from diameter and circumference in mm.',
        ],
        'gold-value-calculator' => [
            'title' => 'Gold Purity & Value Calculator',
            'blurb' => 'Work out the pure gold content and scrap value of any piece from its karat, weight and the current spot price.',
        ],
        'diamond-price-estimator' => [
            'title' => 'Diamond Price Estimator',
            'blurb' => 'Estimate a diamond’s ballpark price from carat, cut, colour and clarity — and see how the 4Cs move the number.',
        ],
        'birthstone-guide' => [
            'title' => 'Gemstone & Birthstone Guide',
            'blurb' => 'Find the birthstone for any month, with hardness, colour and meaning for every major gem.',
        ],
        'jewelry-care-guide' => [
            'title' => 'Jewelry Care & Cleaning Guide',
            'blurb' => 'Get safe, material-specific cleaning steps — and the warnings that matter — for your piece.',
        ],
        'metal-comparison' => [
            'title' => 'Metal Comparison',
            'blurb' => 'Compare gold, platinum, silver, palladium and titanium on durability, upkeep, hypoallergenic properties and cost.',
        ],
    ];

    public function index()
    {
        return view('tools.index', ['tools' => self::TOOLS]);
    }

    public function show(string $slug)
    {
        abort_unless(isset(self::TOOLS[$slug]), 404);

        return view('tools.'.$slug, [
            'tool' => self::TOOLS[$slug],
            'slug' => $slug,
            'tools' => self::TOOLS,
        ]);
    }
}
