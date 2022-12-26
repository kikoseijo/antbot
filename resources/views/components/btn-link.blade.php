<a {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-yellow-300 active:bg-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-800 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
