@extends('layouts.app')

@section('page-title', 'History Pengajuan Perdin')
@section('page-subtitle', 'Daftar Perdin yang sudah diproses')

@section('content')

    <div class="bg-white shadow rounded-lg p-4">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Nama</th>
                <th class="px-4 py-2 text-left">Kota</th>
                <th class="px-4 py-2 text-left">Tanggal</th>
                <th class="px-4 py-2 text-left">Keterangan</th>
                <th class="px-4 py-2 text-left">Status</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @foreach($trips as $index => $trip)
                <tr>
                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">{{ $trip->user->name }}</td>
                    <td class="px-4 py-3">{{ $trip->originCity->name }} → {{ $trip->destinationCity->name }}</td>
                    <td class="px-4 py-3">
                        {{ $trip->departure_date->format('d M Y') }} - {{ $trip->return_date->format('d M Y') }}
                        <span class="text-gray-400 text-xs">({{ $trip->duration_days }} Hari)</span>
                    </td>
                    <td class="px-4 py-3">{{ $trip->purpose }}</td>
                    <td class="px-4 py-3">{!! $trip->status_badge !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
