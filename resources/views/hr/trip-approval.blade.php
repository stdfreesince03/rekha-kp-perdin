@extends('layouts.app')

@section('page-title', 'Approval Pengajuan Perdin')
@section('page-subtitle', 'Approval Perdin Pegawai')

@section('content')

    <div class="bg-white shadow rounded-lg p-6 space-y-6">

        <div>
            <label class="block text-gray-700 mb-1">Nama</label>
            <input type="text" value="{{ $trip->user->name }}" class="w-full border rounded p-2 bg-gray-100" readonly>
        </div>

        <div class="flex space-x-4">
            <div class="flex-1">
                <label class="block text-gray-700 mb-1">Kota Asal</label>
                <input type="text" value="{{ $trip->originCity->name }}" class="w-full border rounded p-2 bg-gray-100" readonly>
            </div>

            <div class="flex-1">
                <label class="block text-gray-700 mb-1">Kota Tujuan</label>
                <input type="text" value="{{ $trip->destinationCity->name }}" class="w-full border rounded p-2 bg-gray-100" readonly>
            </div>
        </div>

        <div class="flex space-x-4">
            <div class="flex-1">
                <label class="block text-gray-700 mb-1">Tanggal Berangkat</label>
                <input type="text" value="{{ $trip->departure_date->format('d F Y') }}" class="w-full border rounded p-2 bg-gray-100" readonly>
            </div>

            <div class="flex-1">
                <label class="block text-gray-700 mb-1">Tanggal Kembali</label>
                <input type="text" value="{{ $trip->return_date->format('d F Y') }}" class="w-full border rounded p-2 bg-gray-100" readonly>
            </div>
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Keterangan</label>
            <textarea class="w-full border rounded p-2 bg-gray-100" readonly>{{ $trip->purpose }}</textarea>
        </div>

        <div class="grid grid-cols-3 gap-4 bg-gray-50 rounded-lg p-4 text-center text-sm font-medium text-gray-700">
            <div>
                <div class="text-akhdani-primary text-xl font-bold">{{ $trip->duration_days }} Hari</div>
                <div>Total Hari</div>
            </div>

            <div>
                <div class="text-akhdani-primary text-xl font-bold">{{ number_format($trip->distance_km, 0) }} KM</div>
                <div>
                    Rp. {{ number_format($trip->daily_allowance, 0, ',', '.') }} / Hari
                </div>
            </div>

            <div>
                <div class="text-akhdani-primary text-xl font-bold">{{ $trip->formatted_allowance }}</div>
                <div>Total Uang Perdin</div>
            </div>
        </div>

        <div class="flex space-x-4 justify-end mt-6">
            <!-- Reject form with input -->
            <form action="{{ route('hr.trips.reject', $trip->id) }}" method="POST" onsubmit="return confirm('Reject trip?');" class="inline-block">
                @csrf
                <input type="hidden" name="rejection_reason" value="Ditolak oleh HR"> <!-- Simplified -->
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                    Reject
                </button>
            </form>

            <!-- Approve form -->
            <form action="{{ route('hr.trips.approve', $trip->id) }}" method="POST" onsubmit="return confirm('Approve trip?');" class="inline-block">
                @csrf
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Approve
                </button>
            </form>
        </div>
    </div>

@endsection
