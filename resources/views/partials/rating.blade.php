{{-- Facet rating: $rating (1-5, may be null) --}}
@if($rating)
<span class="inline-flex items-center gap-1 align-middle" title="{{ $rating }} out of 5">
    @for($i = 1; $i <= 5; $i++)
        <span class="facet-sm" style="background: {{ $i <= round($rating) ? 'var(--gold)' : 'var(--line)' }}"></span>
    @endfor
    <span class="ml-1 text-xs text-[color:var(--stone)]">{{ number_format($rating, 1) }}</span>
</span>
@endif
