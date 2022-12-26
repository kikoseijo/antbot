<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="">
                <section>
                    <header>
                        <div class="flex justify-between">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Your Antbots') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __("Here you can find a list of all your Antbots.") }}
                                </p>
                            </div>
                            <div class="order-last">
                                <x-btn-link href="{{ route('bots.add') }}" >
                                    <svg class="w-5 h-5 mr-1 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ __('New') }}
                                </x-btn-link>
                            </div>
                    </header>
                    <div class="mt-6 space-y-6">
                        @include('bots.table')
                        <div class="flex items-center text-center">
                            <a href="{{ route('bots.add') }}" class='px-4 py-2 bg-yellow-300 border border-transparent rounded-md font-semibold text-md text-white uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>
                                {{ __('Create new Bot') }}
                            </a>
                        </div>
                    </div>
                    <div class="mt-2">
                        {{ $records->links() }}
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
