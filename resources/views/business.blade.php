@extends('layouts.app')

@section('title', $business->name . ' — ' . $business->city->full_name . ' — JewelFind')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags((string) $business->about), 155))

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    @include('partials.breadcrumbs', ['crumbs' => [
        ['label' => $business->city->full_name, 'url' => route('city.show', $business->city)],
        ['label' => $business->category->name, 'url' => route('city.category', [$business->city, $business->category])],
        ['label' => $business->name],
    ]])

    <div class="mt-6 grid gap-8 lg:grid-cols-[1fr_320px]">

        {{-- Main column --}}
        <div>
            <p class="eyebrow mb-2">{{ $business->category->name }} · {{ $business->city->full_name }}</p>
            <h1 class="font-display text-4xl font-semibold">{{ $business->name }}</h1>
            <div class="mt-2">@include('partials.rating', ['rating' => $business->averageRating()])
                <span class="text-sm text-[color:var(--stone)]">{{ $reviews->count() }} {{ \Illuminate\Support\Str::plural('review', $reviews->count()) }}</span>
            </div>

            {{-- About --}}
            <section class="mt-8">
                <h2 class="font-display text-2xl font-semibold mb-2">About</h2>
                <div class="rule-gold mb-4"></div>
                <p class="leading-relaxed text-gray-700 whitespace-pre-line">{{ $business->about ?: 'No description provided yet.' }}</p>
            </section>

            {{-- Address & hours (main column, mobile-first duplicate of sidebar essentials) --}}
            <section class="mt-8 grid gap-6 sm:grid-cols-2">
                <div class="card p-5">
                    <h3 class="font-display text-xl font-semibold mb-2">Address</h3>
                    <p class="text-sm text-gray-700">{{ $business->address ?: '—' }}</p>
                    <p class="text-sm text-[color:var(--stone)] mt-1">{{ $business->city->full_name }}</p>
                </div>
                <div class="card p-5">
                    <h3 class="font-display text-xl font-semibold mb-2">Opening hours</h3>
                    @if($business->hours)
                        <dl class="text-sm space-y-1">
                            @foreach($business->hours as $day => $time)
                                <div class="flex justify-between gap-4">
                                    <dt class="text-[color:var(--stone)]">{{ $day }}</dt>
                                    <dd class="text-gray-800">{{ $time }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @else
                        <p class="text-sm text-[color:var(--stone)]">Hours not listed.</p>
                    @endif
                </div>
            </section>

            {{-- Map --}}
            @if($business->lat && $business->lng)
                <div class="mt-8 card p-0 overflow-hidden">
                    <div id="bizMap" style="height: 300px"></div>
                </div>
            @endif

            {{-- Deals --}}
            @if($coupons->isNotEmpty())
                <section class="mt-8">
                    <div class="flex flex-wrap items-end justify-between gap-3 mb-2">
                        <h2 class="font-display text-2xl font-semibold">Current deals</h2>
                        <a href="{{ route('coupons.show', $business) }}" class="text-sm font-medium hover:text-[color:var(--gold)]">All {{ $business->name }} coupons &amp; deals →</a>
                    </div>
                    <div class="rule-gold mb-4"></div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach($coupons as $coupon)
                            <div class="card p-5 border-l-4" style="border-left-color:var(--gold)">
                                <p class="font-semibold">{{ $coupon->title }}</p>
                                @if($coupon->discount)<p class="text-lg font-display font-semibold" style="color:var(--gold)">{{ $coupon->discount }}</p>@endif
                                @if($coupon->description)<p class="text-sm text-[color:var(--stone)] mt-1">{{ $coupon->description }}</p>@endif
                                <div class="mt-2 flex items-center justify-between text-sm">
                                    @if($coupon->code)<code class="px-2 py-0.5 bg-[color:var(--paper)] border border-dashed border-[color:var(--champagne)]">{{ $coupon->code }}</code>@endif
                                    @if($coupon->expires_at)<span class="text-xs text-[color:var(--stone)]">Expires {{ $coupon->expires_at->format('M j, Y') }}</span>@endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Alternative stores --}}
            @if($alternatives->isNotEmpty())
                <section class="mt-8">
                    <h2 class="font-display text-2xl font-semibold mb-2">Alternative stores nearby</h2>
                    <div class="rule-gold mb-4"></div>
                    <ul class="divide-y divide-[color:var(--line)] card">
                        @foreach($alternatives as $alt)
                            <li class="p-4 flex items-center justify-between gap-4">
                                <div>
                                    <a href="{{ route('business.show', $alt) }}" class="font-medium hover:text-[color:var(--gold)]">{{ $alt->name }}</a>
                                    <p class="text-sm text-[color:var(--stone)]">{{ $alt->address }}</p>
                                </div>
                                @include('partials.rating', ['rating' => $alt->averageRating()])
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            {{-- Reviews --}}
            <section class="mt-10" id="reviews">
                <h2 class="font-display text-2xl font-semibold mb-2">Reviews</h2>
                <div class="rule-gold mb-4"></div>

                @if(session('review_status'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 text-sm">{{ session('review_status') }}</div>
                @endif

                @forelse($reviews as $review)
                    <article class="card p-5 mb-3">
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-medium">{{ $review->author_name }}</span>
                            @include('partials.rating', ['rating' => $review->rating])
                        </div>
                        <p class="mt-2 text-sm leading-relaxed text-gray-700">{{ $review->body }}</p>
                        <p class="mt-2 text-xs text-[color:var(--stone)]">{{ $review->created_at->format('M j, Y') }}</p>
                    </article>
                @empty
                    <p class="text-[color:var(--stone)] text-sm mb-4">No reviews yet — be the first to write one.</p>
                @endforelse

                {{-- Review form --}}
                <div class="card p-6 mt-6">
                    <h3 class="font-display text-xl font-semibold mb-4">Write a review</h3>
                    @if($errors->any())
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-sm">
                            <ul class="list-disc pl-4">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('business.review', $business) }}" class="space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="author_name" class="block text-sm mb-1">Your name *</label>
                                <input id="author_name" name="author_name" value="{{ old('author_name') }}" required class="w-full border border-[color:var(--line)] px-3 py-2 text-sm bg-white">
                            </div>
                            <div>
                                <label for="author_email" class="block text-sm mb-1">Email (not published)</label>
                                <input id="author_email" name="author_email" type="email" value="{{ old('author_email') }}" class="w-full border border-[color:var(--line)] px-3 py-2 text-sm bg-white">
                            </div>
                        </div>
                        <div>
                            <span class="block text-sm mb-2">Rating *</span>
                            <div class="flex gap-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="flex items-center gap-1.5 text-sm cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $i }}" {{ (int) old('rating', 5) === $i ? 'checked' : '' }} class="accent-[#B98B2F]">
                                        {{ $i }}
                                    </label>
                                @endfor
                            </div>
                        </div>
                        <div>
                            <label for="body" class="block text-sm mb-1">Your review *</label>
                            <textarea id="body" name="body" rows="4" required minlength="10" class="w-full border border-[color:var(--line)] px-3 py-2 text-sm bg-white">{{ old('body') }}</textarea>
                        </div>
                        <button type="submit" class="btn-gold px-8 py-2.5 text-sm font-medium">Submit review</button>
                        <p class="text-xs text-[color:var(--stone)]">Reviews appear after moderation.</p>
                    </form>
                </div>
            </section>
        </div>

        {{-- Sidebar --}}
        <aside class="lg:sticky lg:top-6 self-start space-y-4">
            <div class="card p-5" style="background:var(--ink)">
                <p class="eyebrow mb-3">Contact</p>
                <dl class="text-sm space-y-3 text-white/90">
                    <div>
                        <dt class="text-white/50 text-xs uppercase tracking-wider mb-0.5">Address</dt>
                        <dd>{{ $business->address ?: '—' }}<br>{{ $business->city->full_name }}</dd>
                    </div>
                    @if($business->phone)
                        <div>
                            <dt class="text-white/50 text-xs uppercase tracking-wider mb-0.5">Phone</dt>
                            <dd><a href="tel:{{ $business->phone }}" class="hover:text-[color:var(--champagne)]">{{ $business->phone }}</a></dd>
                            @if($business->phone_alt)<dd><a href="tel:{{ $business->phone_alt }}" class="hover:text-[color:var(--champagne)]">{{ $business->phone_alt }}</a></dd>@endif
                        </div>
                    @endif
                    @if($business->website)
                        <div>
                            <dt class="text-white/50 text-xs uppercase tracking-wider mb-0.5">Website</dt>
                            <dd><a href="{{ $business->website }}" target="_blank" rel="nofollow noopener" class="hover:text-[color:var(--champagne)] break-all">{{ parse_url($business->website, PHP_URL_HOST) ?: $business->website }}</a></dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="card p-5">
                <p class="eyebrow mb-3">Opening hours</p>
                @if($business->hours)
                    <dl class="text-sm space-y-1.5">
                        @foreach($business->hours as $day => $time)
                            <div class="flex justify-between gap-4">
                                <dt class="text-[color:var(--stone)]">{{ $day }}</dt>
                                <dd>{{ $time }}</dd>
                            </div>
                        @endforeach
                    </dl>
                @else
                    <p class="text-sm text-[color:var(--stone)]">Not listed.</p>
                @endif
            </div>

            <div class="card p-5">
                <p class="eyebrow mb-3">Browse similar</p>
                <ul class="text-sm space-y-2">
                    <li><a href="{{ route('city.category', [$business->city, $business->category]) }}" class="hover:text-[color:var(--gold)]">{{ $business->category->name }} in {{ $business->city->name }} →</a></li>
                    <li><a href="{{ route('city.show', $business->city) }}" class="hover:text-[color:var(--gold)]">All jewelry businesses in {{ $business->city->name }} →</a></li>
                    @if($coupons->isNotEmpty())
                        <li><a href="{{ route('coupons.show', $business) }}" class="hover:text-[color:var(--gold)]">{{ $business->name }} coupons &amp; deals →</a></li>
                    @endif
                </ul>
            </div>
        </aside>
    </div>
</div>
@endsection

@push('head')
@if($business->lat && $business->lng)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endif
@endpush

@push('scripts')
@if($business->lat && $business->lng)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('bizMap', { scrollWheelZoom: false }).setView([{{ $business->lat }}, {{ $business->lng }}], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
L.marker([{{ $business->lat }}, {{ $business->lng }}], { icon: L.divIcon({
    className: '',
    html: '<div style="width:16px;height:16px;background:#B98B2F;transform:rotate(45deg);border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4)"></div>',
    iconSize: [16,16], iconAnchor: [8,8]
})}).addTo(map).bindPopup({!! json_encode(e($business->name)) !!});
</script>
@endif
@endpush
