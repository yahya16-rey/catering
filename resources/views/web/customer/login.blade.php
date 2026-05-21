@extends('layouts.auth')

@section('content')
<div class="max-w-md w-full mx-auto">
    <!-- Back to Home Link -->
    <div class="mb-4">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-olive-500 transition-colors font-semibold">
            👈 Kembali ke Beranda
        </a>
    </div>

    <div class="bg-white p-8 sm:p-10 rounded-3xl border border-gray-100 shadow-sm">
        <div class="text-center mb-8">
            <span class="w-12 h-12 rounded-full bg-olive-50 text-olive-500 flex items-center justify-center text-xl font-bold mx-auto mb-4">🔑</span>
            <h1 class="font-title font-extrabold text-2xl text-gray-900 mb-2">Selamat Datang Kembali</h1>
            <p class="text-xs text-gray-400 font-semibold">Silakan masuk menggunakan akun Dinda Catering Anda.</p>
        </div>

        <form method="POST" action="{{ route('customer.store_login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password</label>
                <input type="password" id="password" name="password" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-olive-500 border-gray-300 rounded focus:ring-olive-500">
                <label for="remember" class="ml-2 text-xs font-semibold text-gray-500">Ingat Saya</label>
            </div>

            <button type="submit" class="w-full bg-olive-500 hover:bg-olive-600 text-white font-semibold py-3.5 rounded-full text-center text-sm shadow-md shadow-olive-500/10 hover:shadow-lg transition-all">
                Masuk
            </button>

            <div class="text-center pt-4">
                <p class="text-xs font-semibold text-gray-500">
                    Belum punya akun? 
                    <a href="{{ route('customer.register') }}" class="text-olive-500 hover:underline">Daftar sekarang</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection