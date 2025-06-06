@extends('layouts.app')

@section('page-title', 'Tambah Perdin')
@section('page-subtitle', 'Isi data perjalanan dinas')

@section('content')

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('trips.store') }}" method="POST" class="max-w-xl mx-auto bg-white shadow p-6 rounded-lg space-y-4 border border-red-500">
        @csrf

        <div>
            <label class="block text-gray-700 mb-1">Kota Asal</label>
            <select name="origin_city_id" class="w-full border rounded p-2">
                @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Kota Tujuan</label>
            <select name="destination_city_id" class="w-full border rounded p-2">
                @foreach($cities as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Tanggal Berangkat</label>
            <input type="date" name="departure_date" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Tanggal Kembali</label>
            <input type="date" name="return_date" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Keterangan / Purpose</label>
            <input type="text" name="purpose" class="w-full border rounded p-2" placeholder="Contoh: Meeting Project A" required>
        </div>

        <div class="text-right mt-4">
            <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                Simpan Perdin
            </button>
        </div>
    </form>

@endsection
