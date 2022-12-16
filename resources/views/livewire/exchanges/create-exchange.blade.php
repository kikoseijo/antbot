<x-slot name="header">
    <div class="container mx-auto flex flex-row">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add new exchange API') }}
        </h2>
    </div>
</x-slot>
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
    <div class="flex-auto w-full min-w-0 lg:static lg:max-h-full lg:overflow-visible">
        @include('exchanges.form')
    </div>
</section>
