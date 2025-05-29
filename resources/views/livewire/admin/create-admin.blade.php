<div class="p-6 max-w-xl mx-auto bg-white dark:bg-gray-800 shadow rounded">
    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Create New Admin</h2>

    @if (session()->has('message'))
        <div class="mb-4 text-green-600 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
            <input type="text" wire:model="name" class="w-full p-2 rounded border border-gray-300 dark:bg-gray-700 dark:text-white">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input type="email" wire:model="email" class="w-full p-2 rounded border border-gray-300 dark:bg-gray-700 dark:text-white">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
            <input type="password" wire:model="password" class="w-full p-2 rounded border border-gray-300 dark:bg-gray-700 dark:text-white">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Create Admin
        </button>
    </form>
</div>
