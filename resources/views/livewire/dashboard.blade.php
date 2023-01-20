<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-3 grid-flow-col gap-4">
            <div class="">
                <aside aria-label="Sidebar">
                   <div class="overflow-y-auto py-4 px-3 bg-gray-50 sm:rounded-lg dark:bg-gray-800">
                      <ul class="space-y-2">
                          @forelse ($exchanges as $exchange)
                              <li>
                                  <a href="{{ route('exchanges.positions', $exchange->id) }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                      <svg aria-hidden="true" class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
                                      <span class="flex-1 ml-3 whitespace-nowrap">{{ $exchange->name }}</span>
                                      <span class="inline-flex justify-center items-center p-3 ml-3 w-3 h-3 text-sm font-medium text-blue-600 bg-blue-200 rounded-full dark:bg-blue-900 dark:text-blue-200">{{ $exchange->bots_count }}</span>
                                  </a>

                              </li>
                          @empty
                              <li>
                                  <p class="p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white">
                                      <span class="whitespace-nowrap mb-4">No data available.</span>
                                      <br />
                                  </p>
                              </li>
                          @endforelse
                      </ul>
                      <x-btn-link href="{{ route('exchanges.add') }}" class="mt-4 float-right">
                          {{ __('Create exchange') }}
                      </x-btn-link>
                   </div>
                </aside>
            </div>
            <div class="col-span-2">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="text-gray-900 dark:text-gray-100">
                        @if (1 > 2)
                            @foreach ($exchanges as $exchange)
                                <button
                                class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                wire:click="showLogs({{ $exchange->id }})">Show logs {{ $exchange->name }}</button>
                            @endforeach
                        @endif
                        @if (session()->has('exchange_logs'))
                            <div class="alert alert-success">
                                {{ session('exchange_logs') }}
                            </div>
                        @endif
                        @if (session()->has('debug_info'))
                            <div class="alert alert-error">
                                {{ session('debug_info') }}
                            </div>
                        @endif
                        <p class="text-xs">
                            @php
                                $user_auths = auth()->user()->authentications->first();
                            @endphp
                            @if ($user_auths)
                                @php
                                $country = \Arr::get($user_auths->location, 'country');
                                $city = \Arr::get($user_auths->location, 'city');
                                @endphp
                                Last login at: {{ $user_auths->login_at }} from {{ $city }}, {{ $country }} ({{ $user_auths->ip_address }}).
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
