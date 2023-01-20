<button id="botTableDropdown-{{ $bot->id }}" data-dropdown-toggle="botDropdown-{{ $bot->id }}" class="inline-flex items-center px-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600" type="button">
  <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path>
  </svg>
</button>

@php
    $change_status_text = $bot->is_running ? 'Stop bot' : 'Start bot';
@endphp

@push('show-bots-stack')
    <div id="botDropdown-{{ $bot->id }}" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="botTableDropdown-{{ $bot->id }}">
            <li>
                <a href="#change-status-bot" wire:click="changeBotStatus({{ $bot->id }})" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                    {{ $change_status_text }}
                </a>
            </li>
            @if ($bot->is_running)
                <li>
                    <a href="#restart-bot" wire:click="restartBot({{ $bot->id }})" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                        Restart bot
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('bots.edit', $bot) }}" class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                    Edit bot
                </a>
            </li>
            <li>
                <a href="{{ route('bots.logs', [$bot, $bot->symbol->nice_name])}}"
                class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                Show logs
            </a>
        </li>
    </ul>
    <div class="py-1">
        <a wire:click="deleteId({{ $bot->id }})"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-bot-deletion')"
            class="block py-2 px-4 hover:bg-gray-100 text-red-500 dark:hover:bg-gray-600 dark:hover:text-red-700">
            Delete bot
        </a>
    </div>
</div>
@endpush
