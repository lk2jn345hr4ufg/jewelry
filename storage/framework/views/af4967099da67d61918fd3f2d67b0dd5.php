<?php
    $pageTitle = $category
        ? "{$category->name} in {$city->full_name}"
        : "Jewelry Businesses in {$city->full_name}";
?>

<?php $__env->startSection('title', $pageTitle . ' — JewelFind'); ?>
<?php $__env->startSection('meta_description', "Browse {$total} " . ($category ? strtolower($category->name) : 'jewelry') . " businesses in {$city->full_name}: addresses, opening hours, reviews and map."); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 py-8">

    <?php echo $__env->make('partials.breadcrumbs', ['crumbs' => array_filter([
        ['label' => $city->full_name, 'url' => $category ? route('city.show', $city) : null],
        $category ? ['label' => $category->name] : null,
    ])], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="mt-6 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <p class="eyebrow mb-2"><?php echo e($total); ?> <?php echo e(\Illuminate\Support\Str::plural('business', $total)); ?> listed</p>
            <h1 class="font-display text-4xl font-semibold"><?php echo e($pageTitle); ?></h1>
        </div>
        <?php echo $__env->make('partials.search', ['placeholder' => 'Search in ' . $city->name . '…', 'class' => 'lg:max-w-md'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <?php if($cityCategories->isNotEmpty()): ?>
        <div class="mt-6 flex flex-wrap gap-2">
            <a href="<?php echo e(route('city.show', $city)); ?>"
               class="px-3 py-1.5 text-sm border <?php echo e(!$category ? 'btn-gold border-transparent' : 'btn-ghost'); ?>">All categories</a>
            <?php $__currentLoopData = $cityCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('city.category', [$city, $cat])); ?>"
                   class="px-3 py-1.5 text-sm border <?php echo e($category && $category->id === $cat->id ? 'btn-gold border-transparent' : 'btn-ghost'); ?>"><?php echo e($cat->name); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    
    <?php if($mapBusinesses->isNotEmpty()): ?>
        <div class="mt-8 card p-0 overflow-hidden">
            <div id="cityMap" class="w-full" style="height: 380px"></div>
        </div>
    <?php endif; ?>

    
    <div class="mt-8">
        <h2 class="font-display text-2xl font-semibold mb-2">All profiles</h2>
        <div class="rule-gold mb-6"></div>

        <?php if($businesses->isEmpty()): ?>
            <p class="text-[color:var(--stone)]">No businesses listed here yet.
                <a href="<?php echo e(route('home')); ?>" class="text-[color:var(--gold)] hover:underline">Browse other cities</a>.</p>
        <?php else: ?>
            <div id="businessGrid" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <?php $__currentLoopData = $businesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $business): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('partials.business-card', ['business' => $business], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php if($hasMore): ?>
                <div class="mt-6 text-center">
                    <button id="loadMoreBtn" data-offset="<?php echo e($businesses->count()); ?>" class="btn-ghost px-8 py-2.5 text-sm font-medium">Load more</button>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    
    <div class="mt-14">
        <h2 class="font-display text-2xl font-semibold mb-2">Biggest cities</h2>
        <div class="rule-gold mb-6"></div>
        <div class="flex flex-wrap gap-2">
            <?php $__currentLoopData = $biggestCities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($category ? route('city.category', [$bc, $category]) : route('city.show', $bc)); ?>"
                   class="btn-ghost px-3 py-1.5 text-sm <?php echo e($bc->id === $city->id ? 'border-[color:var(--gold)] text-[color:var(--gold)]' : ''); ?>"><?php echo e($bc->full_name); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('head'); ?>
<?php if($mapBusinesses->isNotEmpty()): ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<?php if($mapBusinesses->isNotEmpty()): ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const points = <?php echo json_encode($mapBusinesses, 15, 512) ?>;
const map = L.map('cityMap', { scrollWheelZoom: false });
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
const goldIcon = L.divIcon({
    className: '',
    html: '<div style="width:14px;height:14px;background:#B98B2F;transform:rotate(45deg);border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4)"></div>',
    iconSize: [14, 14],
    iconAnchor: [7, 7]
});
const bounds = [];
points.forEach(p => {
    bounds.push([p.lat, p.lng]);
    L.marker([p.lat, p.lng], { icon: goldIcon })
        .addTo(map)
        .bindPopup(`<strong><a href="${p.url}">${escapeHtml(p.name)}</a></strong><br>${escapeHtml(p.address ?? '')}`);
});
if (bounds.length > 1) map.fitBounds(bounds, { padding: [30, 30] });
else map.setView(bounds[0], 13);
</script>
<?php endif; ?>
<script>
const moreBtn = document.getElementById('loadMoreBtn');
if (moreBtn) {
    moreBtn.addEventListener('click', async function () {
        moreBtn.disabled = true;
        moreBtn.textContent = 'Loading…';
        const offset = parseInt(moreBtn.dataset.offset, 10);
        const url = new URL(`<?php echo e(route('city.businesses', $city)); ?>`, window.location.origin);
        url.searchParams.set('offset', offset);
        <?php if($category): ?> url.searchParams.set('category', '<?php echo e($category->slug); ?>'); <?php endif; ?>
        try {
            const res = await fetch(url);
            const data = await res.json();
            document.getElementById('businessGrid').insertAdjacentHTML('beforeend', data.html);
            moreBtn.dataset.offset = offset + <?php echo e(\App\Http\Controllers\CityController::PER_PAGE); ?>;
            if (!data.hasMore) moreBtn.remove();
            else { moreBtn.disabled = false; moreBtn.textContent = 'Load more'; }
        } catch (e) {
            moreBtn.disabled = false;
            moreBtn.textContent = 'Load more';
        }
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/olegmishyn/Herd/jewelry-directory/resources/views/city.blade.php ENDPATH**/ ?>