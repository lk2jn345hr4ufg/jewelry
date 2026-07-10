
<article class="card p-5 flex flex-col gap-2 transition-colors">
    <div class="flex items-start justify-between gap-3">
        <h3 class="font-display text-xl font-semibold leading-snug">
            <a href="<?php echo e(route('business.show', $business)); ?>" class="hover:text-[color:var(--gold)]"><?php echo e($business->name); ?></a>
        </h3>
        <?php echo $__env->make('partials.rating', ['rating' => $business->averageRating()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    <p class="text-sm" style="color:var(--gold)"><?php echo e($business->category?->name); ?></p>
    <?php if($business->address): ?>
        <p class="text-sm text-[color:var(--stone)]"><?php echo e($business->address); ?></p>
    <?php endif; ?>
    <div class="mt-auto pt-2 flex items-center justify-between text-sm">
        <a href="<?php echo e(route('city.show', $business->city)); ?>" class="text-[color:var(--stone)] hover:text-[color:var(--gold)]"><?php echo e($business->city?->full_name); ?></a>
        <a href="<?php echo e(route('business.show', $business)); ?>" class="font-medium hover:text-[color:var(--gold)]">View profile →</a>
    </div>
</article>
<?php /**PATH /Users/olegmishyn/Herd/jewelry-directory/resources/views/partials/business-card.blade.php ENDPATH**/ ?>