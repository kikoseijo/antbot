
<x-secondary-header :title="__('Users')" />

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="">
                <section>
                    @include('partials.session_message')
                    <header>
                        <div class="flex justify-between">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Listado de usarios') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __("Listado de todos los usuarios dados de alta en el sistema.") }}
                                </p>
                            </div>
                            <div class="order-last">
                                <x-btn-link href="{{ route('users.add') }}" >
                                    <svg class="w-5 h-5 mr-1 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ __('New') }}
                                </x-btn-link>
                            </div>
                    </header>
                    <div class="mt-6 space-y-6">
                        @include('users.table')
                    </div>
                    <div class="mt-2">
                        {{ $records->links() }}
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
