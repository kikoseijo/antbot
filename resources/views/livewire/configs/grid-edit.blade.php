<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="">
                <section>
                    <x-section-header
                        :h2text="__('Advanced grid editor')"
                        :ptext="__('Visual grid editor for your custom grid build.<br />Neat grid has eqty_exp_base.<br />static has secondary_allocation.<br />Recursive has ddown_factor.')" />
                    <div class="mt-6 space-y-6">
                        @include('configs.grid-form')
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @vite(['resources/js/tradingview.js'])
    <div id="modal" style="z-index:1;"
        class="hidden fixed top-0 left-0 z-80 w-screen h-screen bg-black/70 flex justify-center items-center">
        <a class="fixed z-90 top-6 right-8 text-white text-5xl font-bold" href="javascript:void(0)"
            onclick="closeModal()">&times;</a>
        <img id="modal-img" class="max-w-[1200px] max-h-[600px] object-cover" />
    </div>
    <script>
        var modal = document.getElementById("modal");
        var modalImg = document.getElementById("modal-img");
        function showModal(src) {
            modal.classList.remove('hidden');
            modalImg.src = src;
        }
        function closeModal() {
            modal.classList.add('hidden');
        }
    </script>
@endpush
