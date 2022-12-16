
<div class="overflow-x-auto relative rounded-t-xl">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6">#</th>
                <th scope="col" class="py-3 px-6">Name</th>
                <th scope="col" class="py-3 px-6">ApiKey</th>
                <th scope="col" class="py-3 px-6">Date Created</th>
                <th scope="col" class="py-3 px-6"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr class="bg-white dark:bg-gray-800{{ $loop->last ? '' : ' border-b dark:border-gray-700'}}">
                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $record->id }}
                    </th>
                    <td class="py-4 px-6">
                        {{ $record->name }}
                    </td>
                    <td class="py-4 px-6">
                        {{ $record->api_key }}
                    </td>
                    <td class="py-4 px-6">
                        {{ $record->created_at->format('d-m-Y') }}
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="/exchanges/edit/{{ $record->id }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <a wire:click="destroy({{ $record->id }})" class="text-red-600 hover:text-red-900">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
