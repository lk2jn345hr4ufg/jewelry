{{-- Shared: breadcrumb + other-tools footer for a single tool page --}}
@include('partials.breadcrumbs', ['crumbs' => [
    ['label' => 'Tools', 'url' => route('tools.index')],
    ['label' => $tool['title']],
]])
