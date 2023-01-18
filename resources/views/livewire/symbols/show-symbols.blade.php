<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="">
                <section>
                    <x-section-header
                        :h2text="__('Symbols')"
                        :ptext="__('Here you have a list of available trading pairs.')">
                        @if (auth()->user()->email != 'demo@sunnyface.com')
                            <x-btn-link href="{{ route('symbols.add') }}" >
                                <svg class="w-5 h-5 mr-1 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('New') }}
                            </x-btn-link>
                        @endif
                    </x-section-header>
                    <div class="mt-6 space-y-6">
                        <livewire:symbols.symbols-data-table />
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
