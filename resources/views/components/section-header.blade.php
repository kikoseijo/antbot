@props(['h2text', 'ptext'])
<header>
    <div class="flex justify-between">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ $h2text }}
            </h2>
            @isset($ptext)
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $ptext  }}
                </p>
            @endisset
        </div>
        <div class="order-last">
            {{ $slot }}
        </div>
</header>
