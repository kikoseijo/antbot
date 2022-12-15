
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Bots') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="flex justify-center mx-auto p-4 max-w-4xl rounded-lg mb-4 bg-white dark:bg-slate-400 drop-shadow-sm">
            @livewire('bot')
        </div>
    </div>
</x-app-layout>
