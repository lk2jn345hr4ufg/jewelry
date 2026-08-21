<div class="mt-12">
    <h2 class="font-display text-2xl font-semibold mb-2">Other tools</h2>
    <div class="rule-gold mb-5" style="max-width:8rem"></div>
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($tools as $otherSlug => $other)
            @continue($otherSlug === $slug)
            <a href="{{ route('tools.show', $otherSlug) }}" class="flex items-center gap-3 px-4 py-3 border border-[color:var(--line)] hover:border-[color:var(--champagne)] group">
                <span class="facet-sm" style="background:var(--champagne)"></span>
                <span class="font-medium text-sm group-hover:text-[color:var(--gold)]">{{ $other['title'] }}</span>
            </a>
        @endforeach
    </div>
</div>
