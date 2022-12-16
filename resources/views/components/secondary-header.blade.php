@props(['title'])

@if ($title)
    <x-slot name="header">
        <h2 {{ $attributes->merge(['class' => 'font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight']) }}>
            {{ $title }}
        </h2>
    </x-slot>
@endif
