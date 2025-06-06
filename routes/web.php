<?php

use App\Http\Controllers\HRTripController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard routes (after login)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['auth', 'verified', 'role:ADMIN'])->group(function () {
        Route::get('/admin/dashboard', function () {
            $users = \App\Models\User::all();
            return view('admin.dashboard', compact('users'));
        })->name('admin.dashboard');

        Route::post('/admin/users', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'role' => 'required|in:PEGAWAI,SDM,ADMIN',
                'department' => 'nullable|string|max:255',
            ]);

            \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role'],
                'department' => $validated['department'],
                'is_active' => true,
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'User created!');
        })->name('admin.users.store');

        Route::patch('/admin/users/{user}/role', function (\App\Models\User $user, \Illuminate\Http\Request $request) {
            $request->validate([
                'role' => 'required|in:PEGAWAI,SDM,ADMIN',
            ]);

            $user->update(['role' => $request->role]);

            return redirect()->route('admin.dashboard')->with('success', 'User role updated!');
        })->name('admin.users.updateRole');

        Route::delete('/admin/users/{user}', function (\App\Models\User $user) {
            $user->delete();
            return redirect()->route('admin.dashboard')->with('success', 'User deleted!');
        })->name('admin.users.destroy');

    });

    // Employee Dashboard
    Route::middleware(['role:PEGAWAI'])->group(function () {
        Route::get('/dashboard', function () {
            $user = auth()->user();
            $totalTrips = $user->businessTrips()->count();
            $pendingTrips = $user->businessTrips()->pending()->count();
            $approvedTrips = $user->businessTrips()->approved()->count();
            $totalAllowance = $user->businessTrips()->approved()->sum('total_allowance');
            $recentTrips = $user->businessTrips()->latest()->take(5)->get();

            return view('dashboard', compact(
                'totalTrips', 'pendingTrips', 'approvedTrips',
                'totalAllowance', 'recentTrips'
            ));
        })->name('dashboard');
        Route::get('/trips', function () {
            $trips = auth()->user()->businessTrips()->latest()->get();
            return view('trips.index', compact('trips'));
        })->name('trips.index');

        // Tambah Perdin page
        Route::get('/trips/create', function () {
            $cities = \App\Models\City::all();
            return view('trips.create', compact('cities'));
        })->name('trips.create');
        // POST Tambah Perdin
        Route::post('/trips', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'origin_city_id' => 'required|exists:cities,id',
                'destination_city_id' => 'required|exists:cities,id|different:origin_city_id',
                'departure_date' => 'required|date',
                'return_date' => 'required|date|after_or_equal:departure_date',
                'purpose' => 'required|string|max:255',
            ]);

            // Save trip
            auth()->user()->businessTrips()->create(array_merge($validated, [
                'status' => 'PENDING',
            ]));

            return redirect()->route('trips.index')->with('success', 'Perdin berhasil ditambahkan!');
        })->name('trips.store');
    });

    // HR Dashboard
    Route::middleware(['role:SDM'])->group(function () {
        Route::get('/hr/dashboard', function () {
            return view('hr.dashboard');
        })->name('hr.dashboard');

        Route::get('/hr/pending-trips', function () {
            $pendingTrips = \App\Models\BusinessTrip::pending()
                ->with('user', 'originCity', 'destinationCity')
                ->latest()->get();

            return view('hr.pending-trips', compact('pendingTrips'));
        })->name('hr.pending-trips');

        // SDM approval page (view trip)
        Route::get('/hr/trips/{trip}', function (\App\Models\BusinessTrip $trip) {
            return view('hr.trip-approval', compact('trip'));
        })->name('hr.trips.show');

        // Approve trip
        Route::post('/hr/trips/{trip}/approve', function (\App\Models\BusinessTrip $trip) {
            $trip->approve(auth()->id());
            return redirect()->route('hr.pending-trips')->with('success', 'Trip approved.');
        })->name('hr.trips.approve');

        // Reject trip
        Route::post('/hr/trips/{trip}/reject', function (\App\Models\BusinessTrip $trip, \Illuminate\Http\Request $request) {
            $request->validate([
                'rejection_reason' => 'required|string|max:255',
            ]);

            $trip->reject(auth()->id(), $request->rejection_reason);
            return redirect()->route('hr.pending-trips')->with('success', 'Trip rejected.');
        })->name('hr.trips.reject');

        // routes/web.php
        Route::get('/hr/trips-history', [HRTripController::class, 'history'])->name('hr.trips-history');

        Route::get('/cities', [\App\Http\Controllers\CityController::class, 'index'])->name('cities.index');
        Route::post('/cities', [\App\Http\Controllers\CityController::class, 'store'])->name('cities.store');
        Route::delete('/cities/{city}', [\App\Http\Controllers\CityController::class, 'destroy'])->name('cities.destroy');
        Route::get('/cities/{city}/edit', [\App\Http\Controllers\CityController::class, 'edit'])->name('cities.edit');
        Route::patch('/cities/{city}', [\App\Http\Controllers\CityController::class, 'update'])->name('cities.update');

        Route::get('/users', function () {
            return view('users.index');
        })->name('users.index');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
