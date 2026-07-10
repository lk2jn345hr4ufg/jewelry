<?php $__env->startSection('title', 'Stores, Coupons & Deals — JewelFind'); ?>
<?php $__env->startSection('meta_description', 'Current coupons, promo codes and deals from jewelry stores in the JewelFind directory.'); ?>

<?php $__env->startSection('content'); ?>
<section style="background:var(--ink)" class="text-white">
    <div class="max-w-6xl mx-auto px-4 py-12 text-center">
        <p class="eyebrow mb-3">Save on sparkle</p>
        <h1 class="font-display text-4xl sm:text-5xl font-semibold">Stores, Coupons &amp; Deals</h1>
    </div>
</section>

<div class="max-w-6xl mx-auto px-4 py-12">
    <?php if($coupons->isEmpty()): ?>
        <p class="text-[color:var(--stone)]">No active deals right now — check back soon.</p>
    <?php else: ?>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <?php $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card p-5 flex flex-col border-l-4" style="border-left-color:var(--gold)">
                    <?php if($coupon->discount): ?>
                        <p class="font-display text-3xl font-semibold" style="color:var(--gold)"><?php echo e($coupon->discount); ?></p>
                    <?php endif; ?>
                    <p class="font-semibold mt-1"><?php echo e($coupon->title); ?></p>
                    <?php if($coupon->description): ?>
                        <p class="text-sm text-[color:var(--stone)] mt-1"><?php echo e($coupon->description); ?></p>
                    <?php endif; ?>
                    <div class="mt-3 text-sm">
                        <a href="<?php echo e(route('business.show', $coupon->business)); ?>" class="font-medium hover:text-[color:var(--gold)]"><?php echo e($coupon->business->name); ?></a>
                        <span class="text-[color:var(--stone)]"> · <?php echo e($coupon->business->city?->full_name); ?></span>
                    </div>
                    <div class="mt-auto pt-3 flex items-center justify-between">
                        <?php if($coupon->code): ?>
                            <button type="button" onclick="navigator.clipboard.writeText('<?php echo e($coupon->code); ?>'); this.textContent='Copied!'"
                                    class="px-3 py-1 text-sm border border-dashed border-[color:var(--champagne)] bg-[color:var(--paper)] hover:border-[color:var(--gold)]"><?php echo e($coupon->code); ?></button>
                        <?php else: ?>
                            <span class="text-xs text-[color:var(--stone)]">No code needed</span>
                        <?php endif; ?>
                        <?php if($coupon->expires_at): ?>
                            <span class="text-xs text-[color:var(--stone)]">Until <?php echo e($coupon->expires_at->format('M j, Y')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="mt-8"><?php echo e($coupons->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/olegmishyn/Herd/jewelry-directory/resources/views/coupons.blade.php ENDPATH**/ ?>