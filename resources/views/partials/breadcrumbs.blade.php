{{-- $crumbs = [['label' =>, 'url' => nullable]] --}}
<nav aria-label="Breadcrumb" class="text-sm text-[color:var(--stone)]">
    <ol class="flex flex-wrap items-center gap-2">
        <li><a href="{{ route('home') }}" class="hover:text-[color:var(--gold)]">Home</a></li>
        @foreach($crumbs as $crumb)
            <li aria-hidden="true"><span class="facet-sm" style="background:var(--champagne); width:5px; height:5px;"></span></li>
            <li>
                @if(!empty($crumb['url']))
                    <a href="{{ $crumb['url'] }}" class="hover:text-[color:var(--gold)]">{{ $crumb['label'] }}</a>
                @else
                    <span class="text-gray-800">{{ $crumb['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
