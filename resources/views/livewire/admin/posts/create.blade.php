<div class="py-8">
    <div class="max-w-6xl mx-auto bg-white shadow-sm rounded p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Create Post</h2>

        @if (session()->has('message'))
            <div class="mb-4 bg-green-100 text-green-700 px-4 py-2 rounded">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <!-- Title -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Title</label>
                <input type="text" wire:model.defer="title" class="w-full border rounded px-3 py-2" placeholder="Judul post..." />
                @error('title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Image Upload -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Image</label>
                <input type="file" wire:model="newImage" class="w-full border rounded px-3 py-2" />
                @error('newImage') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

                @if ($newImage)
                    <img src="{{ $newImage->temporaryUrl() }}" class="mt-2 w-40">
                @endif
            </div>

            <!-- Content -->
            <div class="mb-4" wire:ignore x-data x-init="
                tinymce.init({
                    selector: '#post-content',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | bold italic underline | link image media | align | numlist bullist | removeformat',
                    height: 300,
                    setup: (editor) => {
                        editor.on('init', () => editor.setContent(@js($content) || ''));
                        editor.on('Change KeyUp', () => {
                            @this.set('content', editor.getContent());
                        });
                    }
                });
            ">
                <label class="block text-sm font-medium mb-1">Content</label>
                <textarea id="post-content" class="w-full border rounded px-3 py-2"></textarea>
                @error('content') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Status</label>
                <select wire:model.defer="status" class="w-full border rounded px-3 py-2">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
                @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
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
            }" x-init="() => {
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

            <!-- Simpan -->
            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Save Post
                </button>
            </div>
        </form>
    </div>
</div>
