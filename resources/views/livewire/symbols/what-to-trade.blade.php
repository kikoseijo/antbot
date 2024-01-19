<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="">
                <section>
                    <x-section-header :h2text="__('Exchange: Bybit')" />
                    <div class="mt-6 space-y-6">
                        <livewire:symbols.what-to-trade-table />
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
