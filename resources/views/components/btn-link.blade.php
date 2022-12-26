<a {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-yellow-300 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-yellow-200 active:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
