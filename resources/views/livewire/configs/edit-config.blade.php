<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="">
                @include('partials.session_message')
                @include('configs.form', ['on_edit' => true])
            </div>
        </div>
    </div>
</div>

@pushOnce('scripts')
    @vite(['resources/js/code-mirror.js'])
@endPushOnce
