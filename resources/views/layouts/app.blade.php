<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Widia Catering' }} - Premium Culinary AI</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Via Play CDN for instant premium previewing) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        olive: {
                            50: '#F4F6F4',
                            100: '#E8EFE9',
                            500: '#5E6F57',
                            600: '#4E5C48',
                            700: '#3D4939',
                        },
                        accent: {
                            500: '#EA580C',
                            600: '#C2410C',
                        },
                        soft: '#F8F9FA'
                    },
                    fontFamily: {
                        outfit: ['Outfit', 'sans-serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8F9FA;
        }
        .font-title {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Premium Navbar -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <span class="w-10 h-10 rounded-full bg-olive-500 flex items-center justify-center text-white font-title font-bold text-xl">W</span>
                        <div class="flex flex-col">
                            <span class="font-title font-bold text-xl text-gray-900 leading-none">Widia Catering</span>
                            <span class="text-xs text-olive-500 font-semibold tracking-wider uppercase">Culinary Intelligence</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-olive-500 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-olive-500 font-semibold' : '' }}">Beranda</a>
                    <a href="{{ route('recommend.index') }}" class="text-gray-600 hover:text-olive-500 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('recommend.*') ? 'text-olive-500 font-semibold' : '' }}">AI Rekomendasi</a>
                    <a href="{{ route('menu.list') }}" class="text-gray-600 hover:text-olive-500 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('menu.*') ? 'text-olive-500 font-semibold' : '' }}">Menu</a>
                    <a href="{{ route('about') }}" class="text-gray-600 hover:text-olive-500 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'text-olive-500 font-semibold' : '' }}">Tentang Kami</a>
                </nav>

                <!-- CTA & Auth -->
                <div class="hidden md:flex items-center gap-4">
                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-500 hover:text-olive-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if(session()->has('cart') && count(session('cart')) > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-accent-500 rounded-full">{{ count(session('cart')) }}</span>
                        @endif
                    </a>

                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-olive-500 text-sm font-medium">Dashboard Admin</a>
                        @else
                            <span class="text-gray-700 text-sm font-medium font-title">Hai, {{ Auth::user()->name }}</span>
                        @endif
                        <form action="{{ route('customer.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-red-500 text-sm font-medium transition-colors">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('customer.login') }}" class="text-gray-700 hover:text-olive-500 text-sm font-medium">Login</a>
                        <a href="{{ route('customer.register') }}" class="bg-olive-500 hover:bg-olive-600 text-white px-5 py-2.5 rounded-full text-sm font-medium shadow-md shadow-olive-500/10 hover:shadow-lg transition-all duration-200">Daftar</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center gap-4">
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if(session()->has('cart') && count(session('cart')) > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-accent-500 rounded-full">{{ count(session('cart')) }}</span>
                        @endif
                    </a>
                    <button id="mobile-menu-btn" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                        <svg class="h-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-b border-gray-100 bg-white">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-olive-500 hover:bg-gray-50">Beranda</a>
                <a href="{{ route('recommend.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-olive-500 hover:bg-gray-50">AI Rekomendasi</a>
                <a href="{{ route('menu.list') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-olive-500 hover:bg-gray-50">Menu</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-olive-500 hover:bg-gray-50">Tentang Kami</a>
                @auth
                    <form action="{{ route('customer.logout') }}" method="POST" class="block w-full">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-red-500 hover:bg-gray-50">Logout</button>
                    </form>
                @else
                    <a href="{{ route('customer.login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-olive-500 hover:bg-gray-50">Login</a>
                    <a href="{{ route('customer.register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-olive-500 hover:bg-gray-50">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Success & Error Alerts -->
    @if(session('successMessage'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl relative flex items-center justify-between" role="alert">
                <span class="block sm:inline">{{ session('successMessage') }}</span>
            </div>
        </div>
    @endif
    @if(session('errorMessage'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl relative flex items-center justify-between" role="alert">
                <span class="block sm:inline">{{ session('errorMessage') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Premium Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12 mt-auto border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Branding -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-10 h-10 rounded-full bg-olive-500 flex items-center justify-center text-white font-title font-bold text-xl">W</span>
                        <span class="font-title font-bold text-xl text-white">Widia Catering</span>
                    </div>
                    <p class="text-sm leading-relaxed max-w-sm mb-4">
                        Elevating Culinary Intelligence with precision-driven nutrition and gourmet recipes. Platform catering modern berbasis AI pertama di Indonesia.
                    </p>
                    <p class="text-xs text-gray-600">
                        &copy; 2026 Widia Catering. All Rights Reserved.
                    </p>
                </div>

                <!-- Quick links -->
                <div>
                    <h3 class="text-white font-title font-semibold mb-4 text-sm uppercase tracking-wider">Tautan Cepat</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('menu.list') }}" class="hover:text-white transition-colors">Menu Harian</a></li>
                        <li><a href="{{ route('recommend.index') }}" class="hover:text-white transition-colors">Rekomendasi AI</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-white font-title font-semibold mb-4 text-sm uppercase tracking-wider">Hubungi Kami</h3>
                    <p class="text-sm mb-2">Jl. Sudirman No. 123, Jakarta</p>
                    <p class="text-sm mb-2">info@widiacatering.com</p>
                    <p class="text-sm">+62 812-3456-7890</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JS Scripts -->
    <script>
        // Toggle mobile menu
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
