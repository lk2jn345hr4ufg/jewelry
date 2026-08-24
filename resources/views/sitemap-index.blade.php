<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($maps as $map)
    <sitemap>
        <loc>{{ $map['loc'] }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </sitemap>
@endforeach
</sitemapindex>
