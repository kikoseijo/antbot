<a {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-yellow-800 dark:bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white dark:text-white uppercase tracking-widest hover:bg-yellow-600 dark:hover:bg-yellow-600 focus:bg-yellow-600 dark:focus:bg-yellow-600 active:bg-yellow-900 dark:active:bg-yellow-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-yellow-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
