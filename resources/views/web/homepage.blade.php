@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gray-900 text-white overflow-hidden py-32 md:py-48">
    <div class="absolute inset-0 z-0">
        <!-- Mock clean culinary overlay/background -->
        <div class="absolute inset-0 bg-gradient-to-r from-gray-950 via-gray-900/90 to-transparent z-10"></div>
        <img src="https://images.unsplash.com/photo-1555244162-803834f70033?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover object-center opacity-40" alt="Catering">
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-semibold bg-olive-500/20 text-olive-100 mb-6 border border-olive-500/30">
                ✨ AI-Personalized Culinary Experience
            </span>
            <h1 class="font-title font-extrabold text-4xl sm:text-5xl md:text-6xl tracking-tight leading-tight mb-6">
                Catering Pintar untuk <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-olive-100 to-olive-500 italic">Bisnis Modern</span>
            </h1>
            <p class="text-lg text-gray-300 mb-10 leading-relaxed">
                Satu-satunya layanan catering yang memadukan keahlian chef bintang lima dengan kecerdasan buatan untuk menyajikan menu yang dipersonalisasi bagi tim Anda.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('menu.list') }}" class="bg-olive-500 hover:bg-olive-600 text-white text-center px-8 py-4 rounded-full font-semibold transition-all shadow-lg shadow-olive-500/20">Mulai Pemesanan</a>
                <a href="{{ route('recommend.index') }}" class="border border-white/20 hover:border-white/40 text-center px-8 py-4 rounded-full font-semibold transition-all">Pelajari Teknologi AI</a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="bg-white border-y border-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <p class="font-title text-4xl font-bold text-olive-500 mb-2">500+</p>
                <p class="text-sm font-semibold text-gray-500 tracking-wider uppercase">Klien Korporat</p>
            </div>
            <div>
                <p class="font-title text-4xl font-bold text-olive-500 mb-2">12k+</p>
                <p class="text-sm font-semibold text-gray-500 tracking-wider uppercase">Porsi Harian</p>
            </div>
            <div>
                <p class="font-title text-4xl font-bold text-olive-500 mb-2">98%</p>
                <p class="text-sm font-semibold text-gray-500 tracking-wider uppercase">Puas</p>
            </div>
            <div>
                <p class="font-title text-4xl font-bold text-olive-500 mb-2">0.5s</p>
                <p class="text-sm font-semibold text-gray-500 tracking-wider uppercase">AI Latency</p>
            </div>
        </div>
    </div>
</section>

<!-- AI Recommended Spotlight Menu Section -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="font-title text-3xl font-extrabold text-gray-900 mb-3">Menu Pilihan AI</h2>
                <p class="text-gray-500 max-w-md">Koleksi menu paling digemari minggu ini, dikurasi secara otomatis untuk keseimbangan rasa dan nutrisi terbaik.</p>
            </div>
            <a href="{{ route('menu.list') }}" class="hidden sm:inline-flex items-center gap-1 text-olive-500 hover:text-olive-600 font-semibold text-sm">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($products as $product)
                <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full group">
                    <div class="relative overflow-hidden aspect-video bg-gray-100">
                        @if($product->gambar)
                            <img src="{{ asset('images/' . $product->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $product->nama_menu }}" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=600&auto=format&fit=crop'">
                        @else
                            <div class="w-full h-full bg-olive-100 flex items-center justify-center text-olive-500 font-bold font-title">Dinda Catering</div>
                        @endif
                        <span class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm text-gray-900 font-semibold text-xs py-1 px-3 rounded-full shadow-sm">{{ $product->kategori }}</span>
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-title font-bold text-lg text-gray-900 group-hover:text-olive-500 transition-colors">{{ $product->nama_menu }}</h3>
                            <div class="flex items-center gap-1 text-amber-500">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span class="text-xs font-bold text-gray-900">{{ number_format($product->rating, 1) }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 line-clamp-2 mb-4">{{ $product->deskripsi }}</p>
                        
                        <div class="flex gap-4 text-xs font-semibold text-gray-500 mb-6 bg-gray-50 p-2.5 rounded-xl">
                            <div>🔥 {{ $product->kalori }} kkal</div>
                            <div>💪 {{ $product->protein }}g Protein</div>
                        </div>

                        <div class="flex justify-between items-center mt-auto">
                            <span class="font-title font-bold text-lg text-gray-900">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            <a href="{{ route('menu.detail', $product->id) }}" class="border border-gray-200 hover:border-olive-500 text-gray-700 hover:text-white hover:bg-olive-500 px-4 py-2 rounded-full text-xs font-semibold transition-all">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- AI Workflow Section -->
<section class="py-24 bg-white border-t border-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-16">
            <h2 class="font-title text-3xl font-extrabold text-gray-900 mb-4">Proses Cerdas Kami</h2>
            <p class="text-gray-500">Mulai dari pemilihan preferensi hingga makanan mendarat di meja Anda, semuanya terintegrasi dengan teknologi AI.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="text-center">
                <div class="w-16 h-16 rounded-3xl bg-olive-50 text-olive-500 flex items-center justify-center font-bold font-title text-xl mx-auto mb-6">1</div>
                <h3 class="font-title font-bold text-lg mb-2 text-gray-900">Analisis Profil</h3>
                <p class="text-sm text-gray-500 max-w-xs mx-auto">Kami menganalisis profil nutrisi, budget, diet, dan pantangan alergi Anda menggunakan model rekomendasi kami.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-3xl bg-olive-50 text-olive-500 flex items-center justify-center font-bold font-title text-xl mx-auto mb-6">2</div>
                <h3 class="font-title font-bold text-lg mb-2 text-gray-900">Rekomendasi AI</h3>
                <p class="text-sm text-gray-500 max-w-xs mx-auto">Algoritma TF-IDF dan Cosine Similarity mencarikan alternatif makanan paling pas dengan presisi tinggi.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-3xl bg-olive-50 text-olive-500 flex items-center justify-center font-bold font-title text-xl mx-auto mb-6">3</div>
                <h3 class="font-title font-bold text-lg mb-2 text-gray-900">Kurasi Chef</h3>
                <p class="text-sm text-gray-500 max-w-xs mx-auto">Setiap menu direkomendasikan disiapkan segar oleh tim kuliner berpengalaman Dinda Catering.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-24 bg-gray-50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-title text-3xl font-extrabold text-gray-900 text-center mb-16">Apa Kata Mereka</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-gray-500 text-sm italic mb-6">"Dinda Catering mengubah cara kami mengatur logistik makan siang kantor. Sangat efisien dan ramah nutrisi!"</p>
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-olive-100 text-olive-500 flex items-center justify-center font-bold text-xs">AM</span>
                    <div>
                        <h4 class="font-bold text-sm text-gray-900">Anisa Maharani</h4>
                        <p class="text-xs text-gray-500">HR Manager, TechCorp</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-gray-500 text-sm italic mb-6">"Sistem AI mereka memangkas waktu perencanaan menu hingga 80%. Sangat membantu untuk event besar!"</p>
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-olive-100 text-olive-500 flex items-center justify-center font-bold text-xs">SK</span>
                    <div>
                        <h4 class="font-bold text-sm text-gray-900">Bambang Kusuma</h4>
                        <p class="text-xs text-gray-500">Event Organizer</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-gray-500 text-sm italic mb-6">"Kualitas rasa bintang lima dengan kemudahan digital. Tidak ada catering lain yang sebanding dengan ini."</p>
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-full bg-olive-100 text-olive-500 flex items-center justify-center font-bold text-xs">SF</span>
                    <div>
                        <h4 class="font-bold text-sm text-gray-900">Santi Fitriani</h4>
                        <p class="text-xs text-gray-500">Individual Customer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection