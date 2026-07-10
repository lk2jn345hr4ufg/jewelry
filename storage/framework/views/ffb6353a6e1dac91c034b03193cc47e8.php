<?php $__env->startSection('title', $business->name . ' — ' . $business->city->full_name . ' — JewelFind'); ?>
<?php $__env->startSection('meta_description', \Illuminate\Support\Str::limit(strip_tags((string) $business->about), 155)); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 py-8">

    <?php echo $__env->make('partials.breadcrumbs', ['crumbs' => [
        ['label' => $business->city->full_name, 'url' => route('city.show', $business->city)],
        ['label' => $business->category->name, 'url' => route('city.category', [$business->city, $business->category])],
        ['label' => $business->name],
    ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="mt-6 grid gap-8 lg:grid-cols-[1fr_320px]">

        
        <div>
            <p class="eyebrow mb-2"><?php echo e($business->category->name); ?> · <?php echo e($business->city->full_name); ?></p>
            <h1 class="font-display text-4xl font-semibold"><?php echo e($business->name); ?></h1>
            <div class="mt-2"><?php echo $__env->make('partials.rating', ['rating' => $business->averageRating()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <span class="text-sm text-[color:var(--stone)]"><?php echo e($reviews->count()); ?> <?php echo e(\Illuminate\Support\Str::plural('review', $reviews->count())); ?></span>
            </div>

            
            <section class="mt-8">
                <h2 class="font-display text-2xl font-semibold mb-2">About</h2>
                <div class="rule-gold mb-4"></div>
                <p class="leading-relaxed text-gray-700 whitespace-pre-line"><?php echo e($business->about ?: 'No description provided yet.'); ?></p>
            </section>

            
            <section class="mt-8 grid gap-6 sm:grid-cols-2">
                <div class="card p-5">
                    <h3 class="font-display text-xl font-semibold mb-2">Address</h3>
                    <p class="text-sm text-gray-700"><?php echo e($business->address ?: '—'); ?></p>
                    <p class="text-sm text-[color:var(--stone)] mt-1"><?php echo e($business->city->full_name); ?></p>
                </div>
                <div class="card p-5">
                    <h3 class="font-display text-xl font-semibold mb-2">Opening hours</h3>
                    <?php if($business->hours): ?>
                        <dl class="text-sm space-y-1">
                            <?php $__currentLoopData = $business->hours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex justify-between gap-4">
                                    <dt class="text-[color:var(--stone)]"><?php echo e($day); ?></dt>
                                    <dd class="text-gray-800"><?php echo e($time); ?></dd>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </dl>
                    <?php else: ?>
                        <p class="text-sm text-[color:var(--stone)]">Hours not listed.</p>
                    <?php endif; ?>
                </div>
            </section>

            
            <?php if($business->lat && $business->lng): ?>
                <div class="mt-8 card p-0 overflow-hidden">
                    <div id="bizMap" style="height: 300px"></div>
                </div>
            <?php endif; ?>

            
            <?php if($coupons->isNotEmpty()): ?>
                <section class="mt-8">
                    <h2 class="font-display text-2xl font-semibold mb-2">Current deals</h2>
                    <div class="rule-gold mb-4"></div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <?php $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card p-5 border-l-4" style="border-left-color:var(--gold)">
                                <p class="font-semibold"><?php echo e($coupon->title); ?></p>
                                <?php if($coupon->discount): ?><p class="text-lg font-display font-semibold" style="color:var(--gold)"><?php echo e($coupon->discount); ?></p><?php endif; ?>
                                <?php if($coupon->description): ?><p class="text-sm text-[color:var(--stone)] mt-1"><?php echo e($coupon->description); ?></p><?php endif; ?>
                                <div class="mt-2 flex items-center justify-between text-sm">
                                    <?php if($coupon->code): ?><code class="px-2 py-0.5 bg-[color:var(--paper)] border border-dashed border-[color:var(--champagne)]"><?php echo e($coupon->code); ?></code><?php endif; ?>
                                    <?php if($coupon->expires_at): ?><span class="text-xs text-[color:var(--stone)]">Expires <?php echo e($coupon->expires_at->format('M j, Y')); ?></span><?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </section>
            <?php endif; ?>

            
            <?php if($alternatives->isNotEmpty()): ?>
                <section class="mt-8">
                    <h2 class="font-display text-2xl font-semibold mb-2">Alternative stores nearby</h2>
                    <div class="rule-gold mb-4"></div>
                    <ul class="divide-y divide-[color:var(--line)] card">
                        <?php $__currentLoopData = $alternatives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="p-4 flex items-center justify-between gap-4">
                                <div>
                                    <a href="<?php echo e(route('business.show', $alt)); ?>" class="font-medium hover:text-[color:var(--gold)]"><?php echo e($alt->name); ?></a>
                                    <p class="text-sm text-[color:var(--stone)]"><?php echo e($alt->address); ?></p>
                                </div>
                                <?php echo $__env->make('partials.rating', ['rating' => $alt->averageRating()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </section>
            <?php endif; ?>

            
            <section class="mt-10" id="reviews">
                <h2 class="font-display text-2xl font-semibold mb-2">Reviews</h2>
                <div class="rule-gold mb-4"></div>

                <?php if(session('review_status')): ?>
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 text-sm"><?php echo e(session('review_status')); ?></div>
                <?php endif; ?>

                <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <article class="card p-5 mb-3">
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-medium"><?php echo e($review->author_name); ?></span>
                            <?php echo $__env->make('partials.rating', ['rating' => $review->rating], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                        <p class="mt-2 text-sm leading-relaxed text-gray-700"><?php echo e($review->body); ?></p>
                        <p class="mt-2 text-xs text-[color:var(--stone)]"><?php echo e($review->created_at->format('M j, Y')); ?></p>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-[color:var(--stone)] text-sm mb-4">No reviews yet — be the first to write one.</p>
                <?php endif; ?>

                
                <div class="card p-6 mt-6">
                    <h3 class="font-display text-xl font-semibold mb-4">Write a review</h3>
                    <?php if($errors->any()): ?>
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-sm">
                            <ul class="list-disc pl-4">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('business.review', $business)); ?>" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="author_name" class="block text-sm mb-1">Your name *</label>
                                <input id="author_name" name="author_name" value="<?php echo e(old('author_name')); ?>" required class="w-full border border-[color:var(--line)] px-3 py-2 text-sm bg-white">
                            </div>
                            <div>
                                <label for="author_email" class="block text-sm mb-1">Email (not published)</label>
                                <input id="author_email" name="author_email" type="email" value="<?php echo e(old('author_email')); ?>" class="w-full border border-[color:var(--line)] px-3 py-2 text-sm bg-white">
                            </div>
                        </div>
                        <div>
                            <span class="block text-sm mb-2">Rating *</span>
                            <div class="flex gap-4">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <label class="flex items-center gap-1.5 text-sm cursor-pointer">
                                        <input type="radio" name="rating" value="<?php echo e($i); ?>" <?php echo e((int) old('rating', 5) === $i ? 'checked' : ''); ?> class="accent-[#B98B2F]">
                                        <?php echo e($i); ?>

                                    </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div>
                            <label for="body" class="block text-sm mb-1">Your review *</label>
                            <textarea id="body" name="body" rows="4" required minlength="10" class="w-full border border-[color:var(--line)] px-3 py-2 text-sm bg-white"><?php echo e(old('body')); ?></textarea>
                        </div>
                        <button type="submit" class="btn-gold px-8 py-2.5 text-sm font-medium">Submit review</button>
                        <p class="text-xs text-[color:var(--stone)]">Reviews appear after moderation.</p>
                    </form>
                </div>
            </section>
        </div>

        
        <aside class="lg:sticky lg:top-6 self-start space-y-4">
            <div class="card p-5" style="background:var(--ink)">
                <p class="eyebrow mb-3">Contact</p>
                <dl class="text-sm space-y-3 text-white/90">
                    <div>
                        <dt class="text-white/50 text-xs uppercase tracking-wider mb-0.5">Address</dt>
                        <dd><?php echo e($business->address ?: '—'); ?><br><?php echo e($business->city->full_name); ?></dd>
                    </div>
                    <?php if($business->phone): ?>
                        <div>
                            <dt class="text-white/50 text-xs uppercase tracking-wider mb-0.5">Phone</dt>
                            <dd><a href="tel:<?php echo e($business->phone); ?>" class="hover:text-[color:var(--champagne)]"><?php echo e($business->phone); ?></a></dd>
                            <?php if($business->phone_alt): ?><dd><a href="tel:<?php echo e($business->phone_alt); ?>" class="hover:text-[color:var(--champagne)]"><?php echo e($business->phone_alt); ?></a></dd><?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if($business->website): ?>
                        <div>
                            <dt class="text-white/50 text-xs uppercase tracking-wider mb-0.5">Website</dt>
                            <dd><a href="<?php echo e($business->website); ?>" target="_blank" rel="nofollow noopener" class="hover:text-[color:var(--champagne)] break-all"><?php echo e(parse_url($business->website, PHP_URL_HOST) ?: $business->website); ?></a></dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </div>

            <div class="card p-5">
                <p class="eyebrow mb-3">Opening hours</p>
                <?php if($business->hours): ?>
                    <dl class="text-sm space-y-1.5">
                        <?php $__currentLoopData = $business->hours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex justify-between gap-4">
                                <dt class="text-[color:var(--stone)]"><?php echo e($day); ?></dt>
                                <dd><?php echo e($time); ?></dd>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </dl>
                <?php else: ?>
                    <p class="text-sm text-[color:var(--stone)]">Not listed.</p>
                <?php endif; ?>
            </div>

            <div class="card p-5">
                <p class="eyebrow mb-3">Browse similar</p>
                <ul class="text-sm space-y-2">
                    <li><a href="<?php echo e(route('city.category', [$business->city, $business->category])); ?>" class="hover:text-[color:var(--gold)]"><?php echo e($business->category->name); ?> in <?php echo e($business->city->name); ?> →</a></li>
                    <li><a href="<?php echo e(route('city.show', $business->city)); ?>" class="hover:text-[color:var(--gold)]">All jewelry businesses in <?php echo e($business->city->name); ?> →</a></li>
                </ul>
            </div>
        </aside>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('head'); ?>
<?php if($business->lat && $business->lng): ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<?php if($business->lat && $business->lng): ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('bizMap', { scrollWheelZoom: false }).setView([<?php echo e($business->lat); ?>, <?php echo e($business->lng); ?>], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
L.marker([<?php echo e($business->lat); ?>, <?php echo e($business->lng); ?>], { icon: L.divIcon({
    className: '',
    html: '<div style="width:16px;height:16px;background:#B98B2F;transform:rotate(45deg);border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4)"></div>',
    iconSize: [16,16], iconAnchor: [8,8]
})}).addTo(map).bindPopup(<?php echo json_encode(e($business->name)); ?>);
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/olegmishyn/Herd/jewelry-directory/resources/views/business.blade.php ENDPATH**/ ?>