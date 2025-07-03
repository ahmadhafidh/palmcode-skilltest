<div class="py-8 max-w-6xl mx-auto bg-white shadow-sm rounded p-6">
    <h2 class="text-xl font-semibold mb-4 text-gray-700">Create New Page</h2>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div>
            <label for="title" class="block font-medium mb-2">Title</label>
            <input type="text" id="title" wire:model="title" class="w-full border rounded px-3 py-2" placeholder="Input Title...">
            @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4" wire:ignore x-data x-init="
            tinymce.init({
                selector: '#pages-body',
                setup(editor) {
                    editor.on('init', () => {
                        editor.setContent(@js($body));
                    });
                    editor.on('change keyup', () => {
                        $wire.set('body', editor.getContent());
                    });
                },
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | bold italic underline | link image media | align | numlist bullist | removeformat',
                height: 300
            });
        ">
            <label for="pages-body" class="block font-medium mb-2">Body</label>
            <textarea id="pages-body" class="w-full border rounded px-3 py-2"></textarea>
            @error('body') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <label for="status" class="block font-medium mb-2">Status</label>
            <select wire:model="status" id="status" class="w-full border rounded px-3 py-2">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
            @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="text-right mt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Save Page
            </button>
        </div>
    </form>
</div>
