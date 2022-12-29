<a {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-teal-300 dark:bg-teal-500 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-teal-400 active:bg-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 dark:focus:ring-offset-teal-500 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
