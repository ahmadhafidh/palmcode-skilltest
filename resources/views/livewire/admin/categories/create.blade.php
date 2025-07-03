<div class="py-8">
    <div class="max-w-6xl mx-auto bg-white shadow-sm rounded p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Create Categories</h2>

        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="save">
            <div class="mb-4">
                <label for="name" class="block font-medium text-gray-700">Name</label>
                <input type="text" id="name" wire:model="name"
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300" placeholder="Input Name...">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create
            </button>
        </form>
    </div>
</div>
