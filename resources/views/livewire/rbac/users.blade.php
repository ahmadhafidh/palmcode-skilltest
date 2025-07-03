<div class="max-w-6xl mx-auto bg-white shadow-sm rounded p-6">
    <h2 class="text-xl font-bold mb-4">Add User</h2>

    @if (session()->has('message'))
        <div class="text-green-600 mb-3">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="store" class="space-y-4 mb-6">
        <div>
            <label class="block font-semibold">Full Name</label>
            <input type="text" wire:model="name" class="w-full border rounded px-3 py-2" placeholder="Enter full name">
            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold">Email Address</label>
            <input type="email" wire:model="email" class="w-full border rounded px-3 py-2" placeholder="Enter email address">
            @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold">User Role</label>
            <select wire:model="selectedRole" class="w-full border rounded px-3 py-2">
                <option value="" disabled>Select a role</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            @error('selectedRole') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold">Password</label>
            <input type="password" wire:model="password" class="w-full border rounded px-3 py-2" placeholder="Enter password">
            @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold">Confirm Password</label>
            <input type="password" wire:model="password_confirmation" class="w-full border rounded px-3 py-2" placeholder="Confirm password">
            @error('password_confirmation') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create User</button>
    </form>

    <h3 class="text-lg font-semibold mb-2">User List</h3>

    <table class="w-full border-collapse border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2 text-left">Name</th>
                <th class="border px-4 py-2 text-left">Email</th>
                <th class="border px-4 py-2 text-left">Role</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td class="border px-4 py-2">{{ $user->name }}</td>
                    <td class="border px-4 py-2">{{ $user->email }}</td>
                    <td class="border px-4 py-2">
                        {{ $user->roles->first()?->name ?? 'No Role' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-gray-500 py-4">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
