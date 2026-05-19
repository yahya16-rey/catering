@extends('layouts.app')

@section('content')
<!-- Header Section -->
<section class="bg-white border-b border-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-title font-extrabold text-3xl text-gray-900 mb-2">AI Food Recommendation</h1>
        <p class="text-gray-500 text-sm">Temukan menu catering terbaik sesuai kebutuhan Anda dengan kecerdasan buatan yang memahami selera dan gaya hidup Anda.</p>
    </div>
</section>

<!-- Main Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Form and Profile Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start mb-16">
            
            <!-- Left: Form -->
            <div class="lg:col-span-2 bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="font-title font-bold text-xl text-gray-900 mb-6 flex items-center gap-2">
                    🎯 Personalisasi Menu
                </h2>
                
                <form action="{{ route('recommend.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Reference Menu -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Menu Acuan</label>
                        <select name="menu_name" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">
                            <option value="">Pilih menu kesukaan Anda...</option>
                            @foreach($menus as $m)
                                <option value="{{ $m->nama_menu }}" {{ isset($selectedMenu) && $selectedMenu == $m->nama_menu ? 'selected' : '' }}>{{ $m->nama_menu }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Diet Preferences -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Preferensi Diet</label>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach(['Diet Sehat', 'Pedas', 'Vegetarian', 'Keto', 'Rendah Karbo'] as $dietOpt)
                                <label class="cursor-pointer">
                                    <input type="radio" name="diet" value="{{ $dietOpt }}" {{ isset($selectedDiet) && $selectedDiet == $dietOpt ? 'checked' : '' }} class="peer sr-only">
                                    <span class="inline-block px-5 py-2.5 rounded-full text-xs font-semibold border border-gray-200 bg-white text-gray-600 peer-checked:bg-olive-500 peer-checked:text-white peer-checked:border-olive-500 hover:border-olive-500 transition-all">{{ $dietOpt }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Budget Slider -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Anggaran Per Porsi</label>
                            <span class="text-sm font-bold text-olive-500" id="budget-value">Rp {{ isset($selectedBudget) ? number_format($selectedBudget, 0, ',', '.') : '85.000' }}</span>
                        </div>
                        <input type="range" name="budget" min="25000" max="150000" step="5000" value="{{ $selectedBudget ?? 85000 }}" oninput="updateBudgetText(this.value)" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-olive-500">
                        <div class="flex justify-between text-[10px] text-gray-400 font-semibold mt-1">
                            <span>Rp 25.000</span>
                            <span>Rp 150.000</span>
                        </div>
                    </div>

                    <!-- Allergies Input -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alergi & Pantangan</label>
                        <input type="text" name="allergies" value="{{ $selectedAllergies ?? '' }}" placeholder="Misal: Kacang, Seafood, Gluten..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">
                    </div>

                    <button type="submit" class="w-full bg-olive-500 hover:bg-olive-600 text-white font-semibold py-4 rounded-full text-center text-sm shadow-md shadow-olive-500/10 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        🥗 Cari Rekomendasi AI
                    </button>
                </form>
            </div>

            <!-- Right: Taste Profile (Interactive Visual Bars) -->
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="font-title font-bold text-xl text-gray-900 mb-6">👅 Profil Rasa Anda</h2>
                <p class="text-xs text-gray-400 font-semibold mb-6">Analisis preferensi berdasarkan preferensi menu dan diet Anda.</p>
                
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-700 mb-1.5">
                            <span>Pedas</span>
                            <span>{{ $tasteProfile['pedas'] ?? 40 }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-accent-500 h-full rounded-full transition-all duration-500" style="width: {{ $tasteProfile['pedas'] ?? 40 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-700 mb-1.5">
                            <span>Gurih (Umami)</span>
                            <span>{{ $tasteProfile['gurih'] ?? 60 }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-olive-500 h-full rounded-full transition-all duration-500" style="width: {{ $tasteProfile['gurih'] ?? 60 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-700 mb-1.5">
                            <span>Manis</span>
                            <span>{{ $tasteProfile['manis'] ?? 30 }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-amber-400 h-full rounded-full transition-all duration-500" style="width: {{ $tasteProfile['manis'] ?? 30 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-700 mb-1.5">
                            <span>Asam</span>
                            <span>{{ $tasteProfile['asam'] ?? 25 }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-orange-400 h-full rounded-full transition-all duration-500" style="width: {{ $tasteProfile['asam'] ?? 25 }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-100 p-5 rounded-2xl mt-8">
                    <p class="text-xs text-gray-500 italic leading-relaxed">
                        "Berdasarkan preferensi Anda, Anda cenderung menyukai makanan yang memiliki profil rasa seimbang dengan penekanan pada rasa gurih alami dan nutrisi yang terjaga."
                    </p>
                </div>
            </div>
        </div>

        <!-- Recommendations Grid -->
        @if(isset($recommendations))
            <div>
                <div class="flex justify-between items-center mb-8">
                    <h2 class="font-title text-2xl font-extrabold text-gray-900">Hasil Rekomendasi</h2>
                    <span class="bg-orange-50 text-accent-500 font-bold text-xs py-1 px-3 rounded-full border border-orange-100">AI Generated</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($recommendations as $product)
                        <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full group relative">
                            <!-- Match Score badge -->
                            <span class="absolute top-4 right-4 z-10 bg-olive-500 text-white font-bold text-[10px] py-1 px-2.5 rounded-full shadow-sm">⭐ {{ $product->match_score }}% Match</span>
                            
                            <div class="relative overflow-hidden aspect-video bg-gray-100">
                                @if($product->gambar)
                                    <img src="{{ asset('images/' . $product->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $product->nama_menu }}" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=600&auto=format&fit=crop'">
                                @else
                                    <div class="w-full h-full bg-olive-100 flex items-center justify-center text-olive-500 font-bold font-title">Widia Catering</div>
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
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
    function updateBudgetText(val) {
        document.getElementById('budget-value').innerText = 'Rp ' + parseInt(val).toLocaleString('id-ID');
    }
</script>
@endsection
