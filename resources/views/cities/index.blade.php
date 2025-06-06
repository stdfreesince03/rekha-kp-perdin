@extends('layouts.app')

@section('page-title', 'Master Kota')
@section('page-subtitle', 'Data Master Kota')

@section('content')

    <!-- Add City Modal trigger -->
    <div class="flex justify-between mb-4">
        <div class="flex space-x-4">
            <a href="{{ route('cities.index') }}" class="{{ $filter === null ? 'font-bold text-blue-600' : 'text-gray-600' }}">Semua</a>
            <a href="{{ route('cities.index', ['filter' => 'domestic']) }}" class="{{ $filter === 'domestic' ? 'font-bold text-blue-600' : 'text-gray-600' }}">Domestic</a>
            <a href="{{ route('cities.index', ['filter' => 'foreign']) }}" class="{{ $filter === 'foreign' ? 'font-bold text-blue-600' : 'text-gray-600' }}">International</a>
            <a href="{{ route('cities.index', ['filter' => 'active']) }}" class="{{ $filter === 'active' ? 'font-bold text-blue-600' : 'text-gray-600' }}">Active Only</a>
        </div>

        <!-- Modal Trigger -->
        <button onclick="document.getElementById('addCityModal').classList.remove('hidden')" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">+ Tambah Kota</button>
    </div>

    <!-- City Table -->
    <div class="bg-white shadow rounded-lg p-4">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Nama Kota</th>
                <th class="px-4 py-2 text-left">Provinsi</th>
                <th class="px-4 py-2 text-left">Pulau</th>
                <th class="px-4 py-2 text-left">Luar Negeri</th>
                <th class="px-4 py-2 text-left">Latitude</th>
                <th class="px-4 py-2 text-left">Longitude</th>
                <th class="px-4 py-2 text-left">Aksi</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @foreach($cities as $index => $city)
                <tr>
                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">{{ $city->name }}</td>
                    <td class="px-4 py-3">{{ $city->province }}</td>
                    <td class="px-4 py-3">{{ $city->island }}</td>
                    <td class="px-4 py-3">{{ $city->is_foreign ? 'Ya' : 'Tidak' }}</td>
                    <td class="px-4 py-3">{{ $city->latitude }}</td>
                    <td class="px-4 py-3">{{ $city->longitude }}</td>
                    <td class="px-4 py-3 flex space-x-2">
                        <a href="{{ route('cities.edit', $city) }}" class="text-orange-500 hover:text-orange-700">✏️</a>
                        <form action="{{ route('cities.destroy', $city) }}" method="POST" onsubmit="return confirm('Delete this city?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">🗑️</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Add City -->
    <div id="addCityModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-lg">
            <h2 class="text-lg font-semibold mb-4">Tambah Kota</h2>
            <form action="{{ route('cities.store') }}" method="POST">
                @csrf
                <div class="space-y-2">
                    <div>
                        <label class="block text-gray-700 mb-1">Nama Kota</label>
                        <input type="text" name="name" class="w-full border rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-1">Provinsi</label>
                        <input type="text" name="province" class="w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-1">Pulau</label>
                        <input type="text" name="island" class="w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-1">Luar Negeri?</label>
                        <select name="is_foreign" class="w-full border rounded p-2">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-1">Negara (jika luar negeri)</label>
                        <input type="text" name="country" class="w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-1">Latitude</label>
                        <input type="text" name="latitude" class="w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-1">Longitude</label>
                        <input type="text" name="longitude" class="w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-1">Aktif?</label>
                        <select name="is_active" class="w-full border rounded p-2">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="document.getElementById('addCityModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

@endsection
