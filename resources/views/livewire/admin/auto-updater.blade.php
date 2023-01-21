<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="">
                <section>
                    <x-section-header
                        :h2text="__('Usefull commands')"
                        :ptext="__('Some of this commands are very powerfull, please proceed under your responsability.')">
                    </x-section-header>
                    <div class="mt-6 space-y-6">
                        <h4 class="font-medium text-gray-900 dark:text-gray-100">Application manintenance & updates</h4>
                        <span class="border-b pt-1 pb-4 w-full block">&nbsp;</span>
                        <button type="button" title="Update application"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-app-update')"
                            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                            Update to latest Antbot version
                        </button>
                    </div>
                </section>
            </div>
        </div>
    </div>


    <x-modal name="confirm-app-update" focusable>
        <div class="p-4">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Antbot?</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('The site will be down during update.') }}
            </p>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-danger-button class="ml-3" wire:click.prevent="updateAntbot()" x-on:click="$dispatch('close')">
                    {{ __('Yes, go for it.') }}
                </x-danger-button>
            </div>
        </div>
    </x-modal>
</div>
