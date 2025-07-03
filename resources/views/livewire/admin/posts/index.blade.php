<div x-data="{ editModalOpen: @entangle('editModalOpen') }" @keydown.escape.window="editModalOpen = false" class="py-8">
    <div class="max-w-6xl mx-auto bg-white shadow-sm rounded p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Post Management</h2>

        @if (session()->has('message'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('posts.create') }}" 
               class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Create Post
            </a>
        </div>

        @if ($posts->isEmpty())
            <!-- No Data Hero -->
            <div class="text-center py-12 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10h.01M9 10h.01M9.75 15c.75.75 2.25.75 3 0" />
                </svg>
                <p class="mt-4 text-lg font-medium">No post found.</p>
            </div>
        @else
            <!-- Tabel Post -->
            <table class="w-full border text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="border px-3 py-2">ID</th>
                        <th class="border px-3 py-2">Title</th>
                        <th class="border px-3 py-2">Slug</th>
                        <th class="border px-3 py-2">Excerpt</th>
                        <th class="border px-3 py-2">Content</th>
                        <th class="border px-3 py-2">Image</th>
                        <th class="border px-3 py-2">Status</th>
                        <th class="border px-3 py-2">Published at </th>
                        <th class="border px-3 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-3 py-2">{{ $post->id }}</td>
                            <td class="border px-3 py-2">{{ $post->title }}</td>
                            <td class="border px-3 py-2">{{ $post->slug }}</td>
                            <td class="border px-3 py-2">
                                {{ \Illuminate\Support\Str::limit(strip_tags($post->excerpt), 50) }}
                            </td>
                            <td class="border px-3 py-2">
                                {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 50) }}
                            </td>
                            <td class="border px-3 py-2">
                                @if ($post->image)
                                    <img src="{{ asset($post->image) }}" class="w-20 h-auto" />
                                @else
                                    <span class="text-gray-400 italic">No image</span>
                                @endif
                            </td>
                            <td class="border px-3 py-2 capitalize">{{ $post->status }}</td>
                            <td class="border px-3 py-2">
                                {{ $post->published_at ?? '-' }}
                            </td>
                            <td class="border px-3 py-2 space-x-2">
                                <button 
                                    wire:click="openEditModal('{{ $post->id }}')" 
                                    type="button"
                                    class="inline-flex items-center rounded bg-blue-700 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300"
                                    title="Edit"
                                >
                                    <svg aria-hidden="true" class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20" 
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                        <path fill-rule="evenodd" d="M2 15.25V18h2.75l7.086-7.086-2.75-2.75L2 15.25z" clip-rule="evenodd"></path>
                                    </svg>
                                    Edit
                                </button>

                                <button wire:click="delete('{{ $post->id }}')" 
                                        onclick="return confirm('Hapus post ini?')" 
                                        type="button"
                                        class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700 focus:ring-4 focus:ring-red-300"
                                        title="Delete">
                                    <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" 
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    
    <!-- Modal Edit Post -->
    <div
        x-show="editModalOpen"
        x-transition
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50"
        @click.self="editModalOpen = false"
        aria-modal="true"
        role="dialog"
    >
        <div class="flex items-center justify-center min-h-screen px-4 py-10">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto p-6 relative">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">Edit Post</h3>

                <form wire:submit.prevent="update" class="space-y-4">

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Title</label>
                        <input
                            type="text"
                            wire:model.defer="title"
                            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                        @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Content with TinyMCE -->
                    <div x-data="{ initTiny: false }" x-init="
                        $watch('editModalOpen', value => {
                            if (value && !initTiny) {
                                initTiny = true;
                                setTimeout(() => {
                                    if (tinymce.get('edit-post-content')) {
                                        tinymce.get('edit-post-content').remove();
                                    }
                                    tinymce.init({
                                        selector: '#edit-post-content',
                                        forced_root_block: 'p',
                                        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                                        toolbar: 'undo redo | bold italic underline | link image media | align | numlist bullist | removeformat',
                                        height: 300,
                                        menubar: false,
                                        setup: (editor) => {
                                            editor.on('Change KeyUp', () => {
                                                @this.set('content', editor.getContent());
                                            });
                                        },
                                        init_instance_callback: (editor) => {
                                            editor.setContent(@this.get('content') || '');
                                        }
                                    });
                                }, 400);
                            } else if (!value && tinymce.get('edit-post-content')) {
                                tinymce.get('edit-post-content').remove();
                                initTiny = false;
                            }
                        });
                    " wire:ignore class="mb-4">
                        <label class="block text-sm font-medium mb-1">Content</label>
                        <textarea id="edit-post-content" class="w-full border rounded px-3 py-2"></textarea>
                        @error('content') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select wire:model.defer="status" class="w-full border rounded px-3 py-2">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                        @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Image Upload & Preview -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Current Image</label>
                        @if ($image && file_exists(public_path($image)))
                            <img src="{{ asset($image) }}" alt="Image" class="w-40 h-auto rounded">
                        @else
                            <p class="text-gray-500 text-sm italic">No image uploaded.</p>
                        @endif
                        <label class="block text-sm font-medium mb-1 mt-2">Upload New Image</label>
                        <input type="file" wire:model="newImage" class="w-full border rounded px-3 py-2">
                        @error('newImage') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Categories Multi-Select -->
                    <div class="mb-4" x-data="{
                        open: false,
                        search: '',
                        options: {{ Js::from($categories->pluck('name', 'id')) }},
                        selected: @entangle('selectedCategories'),
                        isReady: false,
                        toggle(id) {
                            const i = this.selected.indexOf(id);
                            if (i > -1){
                                this.selected.splice(i, 1); 
                            } else {
                                this.selected.push(id);
                            }
                        },
                        remove(id) {
                            const i = this.selected.indexOf(id);
                            if (i > -1) this.selected.splice(i, 1);
                        },
                        filteredOptions() {
                            if (!this.search) return Object.entries(this.options);
                            return Object.entries(this.options).filter(([id, name]) =>
                                name.toLowerCase().includes(this.search.toLowerCase())
                            );
                        }
                    }"  x-init="() => {
                            $watch('selected', val => {
                                if (isReady) $dispatch('input', val);
                            });
                            setTimeout(() => { isReady = true }, 300);
                        }"
                        >
                        <label class="block text-sm font-medium mb-1">Categories</label>

                        <div class="relative">
                            <button type="button" @click="open = !open"
                                class="w-full min-h-[42px] bg-white border border-gray-300 rounded-md shadow-sm px-3 py-2 text-left flex flex-wrap gap-1 focus:outline-none focus:ring focus:border-indigo-500 text-sm">
                                <template x-if="selected.length === 0">
                                    <span class="text-gray-500">Select categories</span>
                                </template>
                                <template x-for="id in selected" :key="`selected-${id}`">
                                    <span class="flex items-center bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded-full text-xs">
                                        <span x-text="options[id]" class="mr-1"></span>
                                        <button type="button" @click.stop="remove(id)" class="text-indigo-600 hover:text-indigo-800 leading-none">
                                            &times;
                                        </button>
                                    </span>
                                </template>

                                <span class="ml-auto">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute z-10 mb-1 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-64 overflow-y-auto text-sm"
                                style="bottom: 100%; top: auto;">

                                <div class="p-2">
                                    <input type="text" x-model="search" placeholder="Search categories..."
                                        class="w-full border px-2 py-1 rounded text-sm focus:outline-none focus:ring focus:border-indigo-500" />
                                </div>

                                <template x-for="[id, name] in filteredOptions()" :key="`option-${id}`">
                                    <div @click="toggle(id)"
                                        class="cursor-pointer select-none px-4 py-2 hover:bg-indigo-100 flex justify-between items-center"
                                        :class="{ 'bg-indigo-50': selected.includes(id) }">
                                        <span x-text="name"></span>
                                        <span x-show="selected.includes(id)" class="text-indigo-600">&#10003;</span>
                                    </div>
                                </template>

                                <div x-show="filteredOptions().length === 0" class="px-4 py-2 text-gray-500 text-sm">
                                    No categories found.
                                </div>
                            </div>
                        </div>

                        @error('selectedCategories')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded border hover:bg-gray-100">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>

