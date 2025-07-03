<div class="max-w-6xl mx-auto bg-white shadow-sm rounded p-6" x-data="{ showForm: false }">
    <h2 class="text-xl font-semibold mb-4 text-gray-700">Assign Roles to User</h2>

    @if(session()->has('message'))
        <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">{{ session('message') }}</div>
    @endif

    <!-- Tombol toggle form -->
    <div class="mb-4">
        <button @click="showForm = !showForm" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            <span x-show="!showForm">Assign Roles</span>
            <span x-show="showForm">Tutup Form</span>
        </button>
    </div>

    <!-- Form assign roles -->
    <form wire:submit.prevent="assignRoles" x-show="showForm" x-transition x-cloak>
        <div class="mb-4">
            <label for="user" class="block mb-1 font-medium">User</label>
            <select wire:model="selectedUser" id="user" class="w-full border rounded px-3 py-2">
                <option value="">-- Select User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
            @error('selectedUser') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div
            class="mb-4"
            x-data="{
                open: false,
                search: '',
                options: {{ Js::from($roles->pluck('name', 'name')) }},
                selected: @entangle('selectedRoles'),
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
            }"
        >
            <label class="block mb-1 font-medium">Roles</label>

            <div class="relative">
                <button type="button" @click="open = !open"
                    class="w-full min-h-[42px] bg-white border rounded px-3 py-2 text-left flex flex-wrap gap-1 focus:outline-none focus:ring focus:border-indigo-500 text-sm">
                    <template x-if="selected.length === 0">
                        <span class="text-gray-500">Select roles</span>
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
                        <input type="text" x-model="search" placeholder="Search roles..."
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
                        No roles found.
                    </div>
                </div>
            </div>
            @error('selectedRoles')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end mb-4">
            <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">
                Assign
            </button>
        </div>
    </form>

    <hr class="my-6">

    <h3 class="text-lg font-semibold mb-3">Daftar User & Roles</h3>

    <table class="w-full border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-3 py-2">#</th>
                <th class="border px-3 py-2">Nama</th>
                <th class="border px-3 py-2">Email</th>
                <th class="border px-3 py-2">Roles</th>
                <th class="border px-3 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($usersWithRoles as $user)
                <tr>
                    <td class="border px-3 py-2">{{ $loop->iteration }}</td>
                    <td class="border px-3 py-2">{{ $user->name }}</td>
                    <td class="border px-3 py-2">{{ $user->email }}</td>
                    <td class="border px-3 py-2">
                        @if ($user->roles->isNotEmpty())
                            @foreach ($user->roles as $role)
                                <span class="inline-block bg-blue-600 text-white text-xs px-3 py-1 rounded mr-1 mb-1">
                                    {{ $role->name }}
                                    <button
                                        wire:click.prevent="deleteRoleFromUser('{{ $user->id }}', '{{ $role->name }}')"
                                        class="ml-1 text-red-200 hover:text-red-400 font-bold text-xs"
                                        title="Remove role">&times;</button>
                                </span>
                            @endforeach
                        @else
                            <span class="text-gray-400 text-xs italic">Belum ada</span>
                        @endif
                    </td>
                    <td class="border px-3 py-2 whitespace-nowrap">
                        <button wire:click.prevent="edit('{{ $user->id }}')"
                            class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 text-xs">
                            Edit
                        </button>
                        <button wire:click.prevent="deleteAllRolesFromUser('{{ $user->id }}')"
                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-xs ml-1"
                            onclick="return confirm('Yakin ingin hapus semua role di user {{ $user->name }}?')">
                            Delete All
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">Belum ada data user.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
