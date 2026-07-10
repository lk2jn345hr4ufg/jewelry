
<form action="<?php echo e(route('search')); ?>" method="GET" class="suggest-wrap relative w-full max-w-2xl <?php echo e($class ?? ''); ?>">
    <div class="flex bg-white border border-[color:var(--line)] overflow-hidden shadow-sm">
        <input
            type="text"
            name="q"
            value="<?php echo e(request('q')); ?>"
            data-suggest
            autocomplete="off"
            placeholder="<?php echo e($placeholder ?? 'Search jewelers, watch repair, engagement rings…'); ?>"
            class="flex-1 min-w-0 px-4 py-3.5 text-[15px] bg-transparent border-0"
        >
        <button type="submit" class="btn-gold px-5 sm:px-7 text-sm font-medium tracking-wide shrink-0">Search</button>
    </div>
    <div class="suggest-box hidden absolute z-30 left-0 right-0 top-full mt-1 bg-white border border-[color:var(--line)] shadow-lg divide-y divide-[color:var(--line)] max-h-80 overflow-auto"></div>
</form>
<?php /**PATH /Users/olegmishyn/Herd/jewelry-directory/resources/views/partials/search.blade.php ENDPATH**/ ?>