<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - Dinda Catering</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Play CDN) -->
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
    
    <!-- Alpine JS for interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F9FAFB;
        }
    </style>
</head>
<body class="min-h-screen flex bg-gray-50 text-gray-800" x-data="{ sidebarOpen: true }">

    <!-- Sidebar (Matching reference layout) -->
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col justify-between flex-shrink-0 transition-all duration-300">
        <div>
            <!-- Header/Branding -->
            <div class="h-20 flex items-center px-8 border-b border-gray-50">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-olive-500 flex items-center justify-center text-white font-title font-bold text-base">D</span>
                    <div class="flex flex-col">
                        <span class="font-title font-bold text-base text-gray-900 leading-none">Dinda Admin</span>
                        <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">Catering Management</span>
                    </div>
                </a>
            </div>

            <!-- Nav Links -->
            <nav class="p-6 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-olive-100/50 text-olive-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>📊</span> Dashboard
                </a>
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('orders.*') ? 'bg-olive-100/50 text-olive-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>📦</span> Data Pesanan
                </a>
                <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all {{ request()->routeIs('products.*') ? 'bg-olive-100/50 text-olive-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <span>🍲</span> Data Menu
                </a>
            </nav>
        </div>

        <!-- Footer / Logout -->
        <div class="p-6 border-t border-gray-50">
            <form action="{{ route('customer.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold text-red-500 hover:bg-red-50 transition-all">
                    <span>🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Topbar -->
        <header class="h-20 bg-white border-b border-gray-50 flex items-center justify-between px-8 relative">
            <!-- Search bar -->
            <div class="w-72 relative">
                <input type="text" placeholder="Cari menu catering..." class="w-full bg-gray-50 border border-gray-200 rounded-full px-4 py-2.5 pl-10 text-xs focus:outline-none focus:border-olive-500 transition-colors">
                <span class="absolute left-3.5 top-3 text-gray-400 text-xs">🔍</span>
            </div>

            <!-- Interactivity Menu Icons -->
            <div class="flex items-center gap-6">
                <!-- Notifications Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="relative text-gray-400 hover:text-gray-600 transition-colors text-lg focus:outline-none p-1.5 rounded-full hover:bg-gray-50">
                        🔔
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-accent-500 rounded-full"></span>
                    </button>
                    <!-- Dropdown box -->
                    <div x-show="open" x-cloak x-transition class="absolute right-0 mt-3 w-80 bg-white border border-gray-100 rounded-3xl shadow-xl py-4 z-50 text-left">
                        <div class="px-5 pb-2 mb-2 border-b border-gray-50 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-900">Notifikasi</span>
                            <span class="text-[10px] text-olive-500 font-bold bg-olive-50 px-2 py-0.5 rounded-full">3 Baru</span>
                        </div>
                        <div class="max-h-60 overflow-y-auto divide-y divide-gray-50">
                            <a href="{{ route('orders.index') }}" class="px-5 py-3 hover:bg-gray-50 block transition-colors">
                                <span class="text-[11px] font-bold text-gray-900 block">📦 Pesanan Baru Masuk</span>
                                <span class="text-[10px] text-gray-400">Order #12 membutuhkan konfirmasi pengiriman katering.</span>
                            </a>
                            <a href="{{ route('products.index') }}" class="px-5 py-3 hover:bg-gray-50 block transition-colors">
                                <span class="text-[11px] font-bold text-amber-600 block">⚠️ Stok Menipis</span>
                                <span class="text-[10px] text-gray-400">Menu 'Wagyu Steak Box' sisa 2 porsi. Harap update stok.</span>
                            </a>
                            <div class="px-5 py-3">
                                <span class="text-[11px] font-bold text-emerald-600 block">✅ Sistem AI Aktif</span>
                                <span class="text-[10px] text-gray-400">Flask recommendation engine terhubung di port 5000.</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Settings Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="text-gray-400 hover:text-gray-600 transition-colors text-lg focus:outline-none p-1.5 rounded-full hover:bg-gray-50">
                        ⚙️
                    </button>
                    <!-- Settings drop down details -->
                    <div x-show="open" x-cloak x-transition class="absolute right-0 mt-3 w-64 bg-white border border-gray-100 rounded-3xl shadow-xl py-4 z-50 text-left">
                        <div class="px-5 pb-2 mb-2 border-b border-gray-50">
                            <span class="text-xs font-bold text-gray-900">Status Sistem</span>
                        </div>
                        <div class="px-5 py-2 space-y-3 text-[11px]">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Database:</span>
                                <span class="font-bold text-gray-900">SQLite (Lokal)</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Payment:</span>
                                <span class="font-bold text-olive-500">Midtrans Sandbox</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Rekomendasi AI:</span>
                                <span class="font-bold text-emerald-600">Flask (Online)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Profile Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center gap-3 border-l border-gray-100 pl-6 focus:outline-none text-left select-none">
                        <span class="w-8 h-8 rounded-full bg-olive-500 text-white flex items-center justify-center font-bold text-xs font-title">A</span>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-900 flex items-center gap-1">
                                Admin Dinda
                                <span class="text-[8px] text-gray-400">▼</span>
                            </span>
                            <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">Manager</span>
                        </div>
                    </button>

                    <!-- Profile Dropdown panel -->
                    <div x-show="open" x-cloak x-transition class="absolute right-0 mt-3 w-56 bg-white border border-gray-100 rounded-3xl shadow-xl py-2 z-50 text-left">
                        <div class="px-4 py-2 border-b border-gray-50">
                            <span class="text-[10px] text-gray-400 font-bold block uppercase tracking-wider">Masuk Sebagai</span>
                            <span class="text-xs font-bold text-gray-900">admin@dinda.com</span>
                        </div>
                        <div class="p-1.5 space-y-0.5">
                            <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                🏠 Lihat Halaman Depan
                            </a>
                            <a href="{{ route('products.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                🍲 Kelola Menu
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                📦 Kelola Pesanan
                            </a>
                        </div>
                        <div class="border-t border-gray-50 p-1.5">
                            <form action="{{ route('customer.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-red-500 hover:bg-red-50 transition-colors text-left">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Page Content -->
        <main class="p-8 flex-grow">
            <!-- Alert message -->
            @if(session('successMessage'))
                <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-800 px-4 py-3 rounded-2xl text-xs font-semibold">
                    {{ session('successMessage') }}
                </div>
            @endif
            @if(session('errorMessage'))
                <div class="mb-6 bg-rose-50 border border-rose-100 text-rose-800 px-4 py-3 rounded-2xl text-xs font-semibold">
                    {{ session('errorMessage') }}
                </div>
            @endif

            @yield('admin_content')
        </main>
    </div>

</body>
</html>
