<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-teal-300 dark:bg-teal-500 border border-transparent rounded-md font-semibold text-xs text-white dark:text-white uppercase tracking-widest hover:bg-teal-400 dark:hover:bg-teal-400 focus:bg-teal-500 dark:focus:bg-teal-500 active:bg-teal-500 dark:active:bg-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 dark:focus:ring-offset-teal-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
