@extends('layouts.app')

@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'Manage the system')

@section('content')

    <div class="bg-white shadow rounded-lg p-6 space-y-6">

        <h2 class="text-xl font-semibold mb-4">User Management</h2>

        <!-- Success Message -->
        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
        @endif

        <!-- Add User Form -->
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-3 mb-6">
            @csrf
            <input name="name" placeholder="Name" class="border p-2 w-full" required>
            <input name="email" placeholder="Email" class="border p-2 w-full" type="email" required>
            <input name="password" placeholder="Password" class="border p-2 w-full" type="password" required>
            <select name="role" class="border p-2 w-full" required>
                <option value="PEGAWAI">PEGAWAI</option>
                <option value="SDM">SDM</option>
                <option value="ADMIN">ADMIN</option>
            </select>
            <input name="department" placeholder="Department" class="border p-2 w-full">

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add User</button>
        </form>

        <!-- User List -->
        <table class="min-w-full border text-sm">
            <thead>
            <tr class="bg-gray-100 text-left">
                <th class="border p-2">#</th>
                <th class="border p-2">Name</th>
                <th class="border p-2">Email</th>
                <th class="border p-2">Role</th>
                <th class="border p-2">Department</th>
                <th class="border p-2">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $index => $user)
                <tr>
                    <td class="border p-2">{{ $index + 1 }}</td>
                    <td class="border p-2">{{ $user->name }}</td>
                    <td class="border p-2">{{ $user->email }}</td>
                    <td class="border p-2">
                        <form method="POST" action="{{ route('admin.users.updateRole', $user) }}">
                            @csrf
                            @method('PATCH')
                            <select name="role" onchange="this.form.submit()" class="border p-1 text-sm">
                                <option value="PEGAWAI" {{ $user->role === 'PEGAWAI' ? 'selected' : '' }}>PEGAWAI</option>
                                <option value="SDM" {{ $user->role === 'SDM' ? 'selected' : '' }}>SDM</option>
                                <option value="ADMIN" {{ $user->role === 'ADMIN' ? 'selected' : '' }}>ADMIN</option>
                            </select>
                        </form>
                    </td>
                    <td class="border p-2">{{ $user->department }}</td>
                    <td class="border p-2">
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

@endsection
