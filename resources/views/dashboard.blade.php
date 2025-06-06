@extends('layouts.app')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . Auth::user()->name)

@section('content')
    <div class="text-center py-8">
        <h2 class="text-xl text-gray-600">Dashboard Content Here</h2>
        <p class="text-gray-500">This is your main dashboard.</p>
    </div>
@endsection
