<div>
@if (session()->has('message'))
    <div x-init="setTimeout(() => show = false, 3000)" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
        {{ session('message') }}
    </div>
@endif
</div>
