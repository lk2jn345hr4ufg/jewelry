
<?php if($rating): ?>
<span class="inline-flex items-center gap-1 align-middle" title="<?php echo e($rating); ?> out of 5">
    <?php for($i = 1; $i <= 5; $i++): ?>
        <span class="facet-sm" style="background: <?php echo e($i <= round($rating) ? 'var(--gold)' : 'var(--line)'); ?>"></span>
    <?php endfor; ?>
    <span class="ml-1 text-xs text-[color:var(--stone)]"><?php echo e(number_format($rating, 1)); ?></span>
</span>
<?php endif; ?>
<?php /**PATH /Users/olegmishyn/Herd/jewelry-directory/resources/views/partials/rating.blade.php ENDPATH**/ ?>