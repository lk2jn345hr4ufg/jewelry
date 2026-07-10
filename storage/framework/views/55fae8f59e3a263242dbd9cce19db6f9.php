
<nav aria-label="Breadcrumb" class="text-sm text-[color:var(--stone)]">
    <ol class="flex flex-wrap items-center gap-2">
        <li><a href="<?php echo e(route('home')); ?>" class="hover:text-[color:var(--gold)]">Home</a></li>
        <?php $__currentLoopData = $crumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li aria-hidden="true"><span class="facet-sm" style="background:var(--champagne); width:5px; height:5px;"></span></li>
            <li>
                <?php if(!empty($crumb['url'])): ?>
                    <a href="<?php echo e($crumb['url']); ?>" class="hover:text-[color:var(--gold)]"><?php echo e($crumb['label']); ?></a>
                <?php else: ?>
                    <span class="text-gray-800"><?php echo e($crumb['label']); ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>
</nav>
<?php /**PATH /Users/olegmishyn/Herd/jewelry-directory/resources/views/partials/breadcrumbs.blade.php ENDPATH**/ ?>