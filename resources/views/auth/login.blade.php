@extends('layouts.guest')

@section('content')
    <div class="bg-white rounded-2xl shadow-2xl p-8 backdrop-blur-sm bg-opacity-95">
        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-akhdani-primary to-akhdani-orange bg-clip-text text-transparent">
                AKHDANI
            </h1>
            <p class="text-gray-600 mt-2">Perjalanan Dinas Management System</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Session Status -->
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input
                    id="email"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-akhdani-primary focus:border-transparent transition duration-200 @error('email') border-red-300 @enderror"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Enter your email"
                />
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <input
                        id="password"
                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-akhdani-primary focus:border-transparent transition duration-200 @error('password') border-red-300 @enderror"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    />
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePassword()">
                        <svg class="h-5 w-5 text-gray-400" id="toggleIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-akhdani-primary focus:ring-akhdani-primary border-gray-300 rounded">
                <label for="remember_me" class="ml-2 block text-sm text-gray-700">Remember me</label>
            </div>

            <!-- Login Button -->
            <button type="submit" class="w-full btn-akhdani py-3 text-lg font-semibold">
                Log In
            </button>

            <!-- Forgot Password -->
            @if (Route::has('password.request'))
                <div class="text-center">
                    <a class="text-sm text-akhdani-primary hover:text-akhdani-orange transition duration-200" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                </div>
            @endif
        </form>

        <!-- Demo Accounts -->
        <div class="mt-8 p-4 bg-blue-50 rounded-lg border-l-4 border-akhdani-primary">
            <h4 class="text-sm font-medium text-akhdani-primary mb-2">Demo Accounts</h4>
            <div class="text-xs text-gray-600 space-y-1">
                <div><strong>Employee:</strong> pegawai@akhdani.com / password</div>
                <div><strong>HR:</strong> sdm@akhdani.com / password</div>
                <div><strong>Admin:</strong>admin@akhdani.com / password</div>
            </div>
        </div>
    </div>
@endsection
