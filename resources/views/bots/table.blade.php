<table class="table-auto w-full">
    <thead>
        <tr class="bg-gray-200">
            <th class="px-4 py-2">#</th>
            <th class="px-4 py-2">Nombre</th>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $item)
            <tr class="text-center">
                <td class="border px-4 py-2">{{ $item->id }}</td>
                <td class="border px-4 py-2">{{ $item->name }}</td>
                <td class="border px-4 py-2">{{ $item->email }}</td>
                <td class="border px-4 py-2">
                    <button wire:click="edit({{ $item->id }})" class="px-2 py-1 bg-blue-200 text-blue-500 hover:bg-blue-500 hover:text-white rounded">Edit</button>
                    <button wire:click="deleteId({{ $item->id }})" data-toggle="modal" data-target="#deleteModal" class="px-2 py-1 bg-red-200 text-red-500 hover:bg-red-500 hover:text-white rounded">Delete</button>
                </td>
            </tr>
        @empty
            <tr class="text-center">
                <td colspan="4" class="py-3 italic">No hay información</td>
            </tr>
        @endforelse
    </tbody>
</table>
