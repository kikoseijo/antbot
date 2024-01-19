<x-guest-layout>
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0 bitcoin-background">
        @if (Route::has('login'))
            <div class="fixed top-0 right-0 px-6 py-4 sm:block">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</a>
                    {{-- @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
                    @endif --}}
                @endauth
            </div>
        @endif

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-center pt-8 sm:pt-0">
                <x-application-footer-logo class="h-10 sm:h-20" />
            </div>


            <div class="flex justify-center mt-8 sm:items-center sm:justify-between">
                <div class="text-center text-sm text-gray-500 sm:text-left w-full">
                    <div class="flex justify-center w-full">
                        Made with
                        <svg fill="none" stroke="#ffd152" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="ml-1 mr-1 -mt-px w-5 h-5 greener">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        in Spain by
                        <a href="https://sunnyface.com" class="ml-1 underline">
                            Sunnyface.com
                        </a>
                        .
                    </div>
                </div>


            </div>
        </div>
    </div>
</x-guest-layout>
