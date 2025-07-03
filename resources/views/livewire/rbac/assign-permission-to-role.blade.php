<div class="max-w-6xl mx-auto bg-white shadow-sm rounded p-6" x-data="{ showForm: @entangle('showForm') }">
    <h2 class="text-xl font-semibold mb-4 text-gray-700">Assign Permission to Role</h2>

    @if (session()->has('message'))
        <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tombol toggle form -->
    <div class="mb-4" x-show="!showForm" x-cloak>
        <button @click="showForm = true" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
            Assign Permission
        </button>
    </div>

    <!-- Form assign permission -->
    <form wire:submit.prevent="assign" x-show="showForm" x-transition x-cloak>
        <!-- Role -->
        <div class="mb-4">
            <label for="role" class="block mb-1 font-medium">Role</label>
            <select wire:model="selectedRole" id="role" class="w-full border rounded px-3 py-2">
                <option value="">-- Select Role --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
            @error('selectedRole')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Permissions -->
        <div class="mb-4" x-data="{
                open: false,
                search: '',
                options: {{ Js::from($permissions->pluck('name', 'name')) }},
                selected: @entangle('selectedPermissions'),
                toggle(name) {
                    const i = this.selected.indexOf(name);
                    if (i > -1) this.selected.splice(i, 1);
                    else this.selected.push(name);
                },
                filteredOptions() {
                    if (!this.search) return Object.entries(this.options);
                    return Object.entries(this.options).filter(([key, name]) =>
                        name.toLowerCase().includes(this.search.toLowerCase())
                    );
                }
            }">
            <label class="block mb-1 font-medium">Permissions</label>

            <div class="relative">
                <button type="button" @click="open = !open"
                    class="w-full min-h-[42px] bg-white border rounded px-3 py-2 text-left flex flex-wrap gap-1 focus:outline-none focus:ring focus:border-indigo-500 text-sm">
                    <template x-if="selected.length === 0">
                        <span class="text-gray-500">Select permissions</span>
                    </template>
                    <template x-for="name in selected" :key="name">
                        <span class="flex items-center bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded-full text-xs">
                            <span x-text="options[name]" class="mr-1"></span>
                            <button type="button" @click.stop="toggle(name)"
                                class="text-indigo-600 hover:text-indigo-800 leading-none">&times;</button>
                        </span>
                    </template>
                    <span class="ml-auto">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </button>

                <div x-show="open" @click.away="open = false"
                    class="absolute z-10 mt-1 w-full bg-white border rounded shadow max-h-64 overflow-y-auto text-sm">
                    <div class="p-2">
                        <input type="text" x-model="search" placeholder="Search permissions..."
                            class="w-full border rounded px-2 py-1 text-sm focus:outline-none focus:ring focus:border-indigo-500" />
                    </div>
                    <template x-for="[key, name] in filteredOptions()" :key="key">
                        <div @click="toggle(key)"
                            class="cursor-pointer select-none px-4 py-2 hover:bg-indigo-100 flex justify-between items-center"
                            :class="{ 'bg-indigo-50': selected.includes(key) }">
                            <span x-text="name"></span>
                            <span x-show="selected.includes(key)" class="text-indigo-600">&#10003;</span>
                        </div>
                    </template>
                    <div x-show="filteredOptions().length === 0" class="px-4 py-2 text-gray-500 text-sm">
                        No permissions found.
                    </div>
                </div>
            </div>

            @error('selectedPermissions')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Assign -->
        <div class="flex justify-start mb-4">
            <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                Assign
            </button>
        </div>
    </form>

    <hr class="my-6">

    <!-- Table Role & Permissions -->
    <h3 class="text-lg font-semibold mb-3">Daftar Role & Permission</h3>

    <table class="w-full border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-3 py-2">#</th>
                <th class="border px-3 py-2">Role</th>
                <th class="border px-3 py-2">Permissions</th>
                <th class="border px-3 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rolesWithPermissions as $role)
                <tr>
                    <td class="border px-3 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-3 py-2 font-medium">{{ $role->name }}</td>
                    <td class="border px-3 py-2">
                        @if ($role->permissions->isNotEmpty())
                            @foreach ($role->permissions as $perm)
                                <span class="inline-block bg-blue-600 text-white text-xs px-3 py-1 rounded mr-1 mb-1">
                                    {{ $perm->name }}
                                    <button wire:click.prevent="deletePermission('{{ $role->name }}', '{{ $perm->name }}')"
                                        class="ml-1 text-red-200 hover:text-red-400 font-bold text-xs" title="Remove permission">&times;</button>
                                </span>
                            @endforeach
                        @else
                            <span class="text-gray-400 text-xs italic">Belum ada</span>
                        @endif
                    </td>
                    <td class="border px-3 py-2 whitespace-nowrap">
                        <button wire:click.prevent="edit('{{ $role->name }}')" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                            Edit
                        </button>
                        <button wire:click.prevent="deleteAllPermissions('{{ $role->name }}')" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-xs ml-1"
                            onclick="return confirm('Yakin ingin hapus semua permission di role {{ $role->name }}?')">
                            Delete All
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-500">Belum ada data role.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
