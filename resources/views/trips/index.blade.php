@extends('layouts.app')

@section('page-title', 'PerdinKu')
@section('page-subtitle', 'Daftar Perjalanan Dinas')

@section('content')

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">PerdinKu</h1>
        <a href="{{ route('trips.create') }}"
           class="inline-flex items-center px-4 py-2 border border-blue-400 text-blue-500 text-sm font-medium rounded-md hover:bg-blue-50 transition">
            <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Perdin
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-4">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Rute</th>
                <th class="px-4 py-2 text-left">Tanggal</th>
                <th class="px-4 py-2 text-left">Keterangan</th>
                <th class="px-4 py-2 text-left">Distance</th>
                <th class="px-4 py-2 text-left">Allowance</th>
                <th class="px-4 py-2 text-left">Status</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @foreach($trips as $index => $trip)
                <tr>
                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">{{ $trip->route_display }}</td>
                    <td class="px-4 py-3">
                        {{ $trip->departure_date->format('d M Y') }} - {{ $trip->return_date->format('d M Y') }}
                        <span class="text-gray-400 text-xs">({{ $trip->duration_days }} Hari)</span>
                    </td>
                    <td class="px-4 py-3">{{ $trip->purpose }}</td>
                    <td class="px-4 py-3">{{ $trip->distance_km }} km</td>
                    <td class="px-4 py-3">{{ $trip->formatted_allowance }}</td>
                    <td class="px-4 py-3">{!! $trip->status_badge !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
