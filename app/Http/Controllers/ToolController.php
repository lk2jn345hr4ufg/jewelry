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
        'engagement-ring-budget' => [
            'title' => 'Engagement Ring Budget Calculator',
            'blurb' => 'Work out a sensible ring budget from your actual savings, income and timeline — not the old “three months’ salary” rule.',
        ],
        'carat-size-visualizer' => [
            'title' => 'Carat Size Visualizer',
            'blurb' => 'See how big a diamond really looks at any carat weight, drawn to scale across eight shapes.',
        ],
        'diamond-shape-guide' => [
            'title' => 'Diamond Shape Selector',
            'blurb' => 'Compare shapes on sparkle, size-per-carat, value and toughness — and get a pick for your style and hand.',
        ],
        'gemstone-alternatives' => [
            'title' => 'Gemstone Alternatives Finder',
            'blurb' => 'Want the look of a diamond or emerald for less? Find durable stand-ins, with honest notes on each trade-off.',
        ],
        'necklace-length-guide' => [
            'title' => 'Necklace & Chain Length Guide',
            'blurb' => 'Find the right chain length for your neckline, build and layering plans, from choker to opera.',
        ],
        'bracelet-size-calculator' => [
            'title' => 'Bracelet & Bangle Size Calculator',
            'blurb' => 'Turn a wrist measurement into the right bracelet length for a snug, comfortable or loose fit.',
        ],
        'watch-size-calculator' => [
            'title' => 'Watch Size Calculator',
            'blurb' => 'Find the case diameter, lug-to-lug span and strap width that will actually suit your wrist.',
        ],
        'jewelry-gift-finder' => [
            'title' => 'Jewelry Gift Finder',
            'blurb' => 'Pick recipient, occasion and budget for specific gift ideas — including which need no sizing.',
        ],
        'anniversary-gift-guide' => [
            'title' => 'Anniversary Gift Guide',
            'blurb' => 'The traditional material, modern gift and gemstone for every anniversary year, with jewelry ideas.',
        ],
        'ring-cost-calculator' => [
            'title' => 'True Cost of Ownership',
            'blurb' => 'What a ring really costs once financing interest, insurance and decades of upkeep are counted.',
        ],
        'hallmark-guide' => [
            'title' => 'Hallmark Decoder',
            'blurb' => 'Decode the tiny stamps inside your jewelry — 925, 750, PLAT, GF — to find out what metal you actually have.',
        ],
        'silver-value-calculator' => [
            'title' => 'Silver & Platinum Value Calculator',
            'blurb' => 'Melt value for sterling, platinum and palladium from weight, purity and spot price.',
        ],
        'chain-type-guide' => [
            'title' => 'Chain Type Guide',
            'blurb' => 'Compare cable, curb, rope, box and more on strength and repairability — which chains last and which kink forever.',
        ],
        'setting-type-guide' => [
            'title' => 'Ring Setting Guide',
            'blurb' => 'Prong, bezel, pavé, tension and more, rated on security, sparkle and snagging for your lifestyle.',
        ],
        'metal-allergy-checker' => [
            'title' => 'Metal Allergy Checker',
            'blurb' => 'Which metals are safe for nickel allergies and sensitive skin — and what to do about pieces you already own.',
        ],
        'jewelry-insurance-calculator' => [
            'title' => 'Jewelry Insurance Calculator',
            'blurb' => 'Find out how much of your collection your home policy actually covers, and what a rider would cost.',
        ],
        'diamond-certificate-guide' => [
            'title' => 'Diamond Certificate Decoder',
            'blurb' => 'Understand every line of a GIA report — cut, fluorescence, proportions — and which labs grade honestly.',
        ],
        'vintage-era-guide' => [
            'title' => 'Vintage Era Identifier',
            'blurb' => 'Identify Georgian, Victorian, Art Nouveau, Edwardian, Deco or Retro from style, metal and construction.',
        ],
        'watch-water-resistance' => [
            'title' => 'Watch Water Resistance Decoder',
            'blurb' => 'What your watch’s ATM rating really permits — 30 m does not mean you can swim in it.',
        ],
        'gemstone-durability-checker' => [
            'title' => 'Gemstone Durability Checker',
            'blurb' => 'Can that stone survive daily wear? Hardness, toughness and cleavage risk for 27 gemstones.',
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
