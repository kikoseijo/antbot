@props(['h2text', 'h3text', 'h4text', 'ptext'])
<header>
    <div class="flex justify-between">
        <div>
            @isset($h2text)
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ $h2text }}
                </h2>
            @endisset
            @isset($h3text)
                <h3 class="text-xl font-medium text-gray-900 dark:text-gray-100">
                    {{ $h3text }}
                </h3>
            @endisset
            @isset($h4text)
                <h4 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ $h4text }}
                </h4>
            @endisset
            @isset($ptext)
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {!! $ptext  !!}
                </p>
            @endisset
        </div>
        <div class="order-last">
            {{ $slot }}
        </div>
</header>
