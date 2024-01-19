@php
    $twe = \Arr::get($total_wallet_exposure, 'USDT');
    $total_twe = number($twe * 100);
@endphp
<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col m-auto p-auto">
        <div class="flex overflow-x-scroll">
            <div class="flex flex-nowrap">
                <div class="inline-block w-96 max-w-xs sm:w-max overflow-hidden mr-3 px-6 pt-6 bg-white border border-gray-200 rounded-lg shadow-xs dark:bg-gray-800 dark:border-gray-700">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Wallet Exposure</h5>
                    <p class="text-xl text-center text-gray-900 dark:text-white">{{ $total_twe }}%</p>
                    <canvas id="twe-gauge" class="w-64 ml-0 pl-0"></canvas>
                </div>
                @foreach ($balances as $balance)
                    @include('exchanges.balance-card', ['balance' => $balance])
                @endforeach
            </div>
        </div>
        </div>
        <div class="mt-8 space-y-6">
            <livewire:exchanges.positions-table />
        </div>
    </div>
</div>



@push('scripts')
    <script src="{{ asset('js/gauge.min.js') }}" charset="utf-8"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            var opts = {
              // percentColors:[[0.0, "#31C48D" ], [0.18, "#31C452"], [0.28, "#F05252"]],
              staticZones: [
                   {strokeStyle: "#31C452", min: 0, max: 180}, // Red from 100 to 130
                   {strokeStyle: "#FACA15", min: 181, max: 399}, // Yellow
                   {strokeStyle: "#F05252", min: 400, max: 1000}, // Green
            ],
              angle: -0.09, // The span of the gauge arc
              lineWidth: 0.35, // The line thickness
              radiusScale: 0.80, // Relative radius
              pointer: {
                length: 0.78, // // Relative to gauge radius
                strokeWidth: 0.065, // The thickness
                color: '#fff' // Fill color
              },
              limitMax: false,     // If false, max value increases automatically if value > maxValue
              limitMin: false,     // If true, the min value of the gauge will be fixed
              // colorStart: '#6F6EA0',   // Colors
              // colorStop: '#C0C0DB',    // just experiment with them
              // strokeColor: '#F05252',  // red
              generateGradient: true,
              staticLabels: {
                  font: "12px sans-serif",  // Specifies font
                  labels: [100, 200, 300, 400, 800],  // Print labels at these values
                  color: "#fff",  // Optional: Label text color
                  fractionDigits: 0  // Optional: Numerical precision. 0=round off.
                },
              highDpiSupport: true,     // High resolution support
            };
            var target = document.getElementById('twe-gauge'); // your canvas element
            var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
            gauge.maxValue = 1000; // set max gauge value
            gauge.setMinValue(0);  // Prefer setter over gauge.minValue = 0
            gauge.animationSpeed = 32; // set animation speed (32 is default value)
            gauge.set({{ $total_twe }}); // set actual value
        });
    </script>
@endpush
