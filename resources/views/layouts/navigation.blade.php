<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto ml-4 fill-current text-yellow-300 dark:text-yellow-300" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @if (auth() && auth()->user()->admin)
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            {{ __('Users') }}
                        </x-nav-link>
                    @endif
                    @if ($settings->enable_routines || (auth() && auth()->user()->admin))
                        <x-nav-link :href="route('routines.index')" :active="request()->routeIs('routines.*')">
                            {{ __('Routines') }}
                        </x-nav-link>
                    @endif
                    <x-nav-link data-dropdown-toggle="dropdown-trades" href="#" :active="request()->routeIs('trades.*')">
                        {{ __('Trades') }}
                        <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </x-nav-link>
                    <!-- Dropdown menu -->
                    <div id="dropdown-trades" class="z-10 hidden bg-white divide-gray-100 shadow-lg rounded-lg w-44 dark:bg-gray-700">
                        <x-dropdown-link :href="route('trades.pnl')">
                            {{ __('Profit & Loss') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('trades.index')">
                            {{ __('Trades by Symbol') }}
                        </x-dropdown-link>
                    </div>
                    {{-- <x-nav-link :href="route('exchanges.index')" :active="request()->routeIs('exchanges.*')">
                        {{ __('Exchanges') }}
                    </x-nav-link> --}}

                    @if ($settings->enable_positions || (auth() && auth()->user()->admin))
                        <x-nav-link :href="route('positions.index')" :active="request()->routeIs('positions.*')">
                            {{ __('Positions') }}
                        </x-nav-link>
                    @endif
                    @if ($settings->enable_bots || (auth() && auth()->user()->admin))
                        <x-nav-link :href="route('bots.index')" :active="request()->routeIs('bots.*')">
                            {{ __('Bots') }}
                        </x-nav-link>
                    @endif
                    @if ($settings->enable_grids || (auth() && auth()->user()->admin))
                        <x-nav-link :href="route('configs.index')" :active="request()->routeIs('configs.*')">
                            {{ __('Configs') }}
                        </x-nav-link>
                    @endif
                    @if ($settings->enable_what4trade || (auth() && auth()->user()->admin))
                        <x-nav-link :href="route('symbols.what-to-trade')" :active="request()->routeIs('symbols.*')">
                            {{ __('What2Trade') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 mr-3">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                </button>
                @impersonating
                    <a href="{{ route('impersonate.leave') }}" title="{{ __('Stop impersonating') }}" class="text-yellow-300 dark:text-yellow-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"></path>
                        </svg>
                    </a>
                @endImpersonating
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('User profile') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('exchanges.index')">
                            {{ __('Exchanges') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('symbols.index')">
                            {{ __('Symbols') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('users.auth-logs', auth()->user())">
                            {{ __('User login logs') }}
                        </x-dropdown-link>
                        <hr>
                        @if (auth() && auth()->user()->admin)
                            <x-dropdown-link href="/klogs" target="_blank" class="flex content-center">
                                {{ __('Application logs') }}
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path>
                                </svg>
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.settings')">
                                {{ __('Settings') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.commands')">
                                {{ __('Admin commands') }}
                            </x-dropdown-link>
                        @endif
                        @impersonating
                        <x-dropdown-link :href="route('impersonate.leave')">
                            {{ __('Stop impersonating') }}
                        </x-dropdown-link>
                        @endImpersonating

                        <hr>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @if (auth() && auth()->user()->admin)
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    {{ __('Users') }}
                </x-responsive-nav-link>
            @endif
            @if ($settings->enable_routines || (auth() && auth()->user()->admin))
                <x-responsive-nav-link :href="route('routines.index')" :active="request()->routeIs('routines.*')">
                    {{ __('Routines') }}
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('trades.pnl')">
                {{ __('Profit & Loss') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('trades.index')">
                {{ __('Trade by symbol') }}
            </x-responsive-nav-link>
            @if ($settings->enable_positions || (auth() && auth()->user()->admin))
                <x-responsive-nav-link :href="route('positions.index')" :active="request()->routeIs('positions.*')">
                    {{ __('Positions') }}
                </x-responsive-nav-link>
            @endif
            @if ($settings->enable_bots || (auth() && auth()->user()->admin))
                <x-responsive-nav-link :href="route('bots.index')" :active="request()->routeIs('bots.*')">
                    {{ __('Bots') }}
                </x-responsive-nav-link>
            @endif
            {{-- <x-responsive-nav-link :href="route('exchanges.index')" :active="request()->routeIs('exchanges.*')">
                {{ __('Exchanges') }}
            </x-responsive-nav-link> --}}
            @if ($settings->enable_grids || (auth() && auth()->user()->admin))
                <x-responsive-nav-link :href="route('configs.index')" :active="request()->routeIs('configs.*')">
                    {{ __('Configs') }}
                </x-responsive-nav-link>
            @endif
            @if ($settings->enable_what4trade || (auth() && auth()->user()->admin))
                <x-responsive-nav-link :href="route('symbols.what-to-trade')" :active="request()->routeIs('symbols.*')">
                    {{ __('What2Trade') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('exchanges.index')">
                    {{ __('Exchanges') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('symbols.index')">
                    {{ __('Symbols') }}
                </x-responsive-nav-link>

                @if (auth() && auth()->user()->admin)
                    <x-responsive-nav-link href="/log-viewer" target="_blank" class="flex content-center">
                        {{ __('Logs') }}
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path>
                        </svg>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.settings')">
                        {{ __('Settings') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.commands')">
                        {{ __('Admin commands') }}
                    </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('users.auth-logs', auth()->user())">
                    {{ __('Login logs') }}
                </x-responsive-nav-link>

                @impersonating
                    <x-responsive-nav-link :href="route('impersonate.leave')">
                        {{ __('Stop impersonating') }}
                    </x-responsive-nav-link>
                @endImpersonating



                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
