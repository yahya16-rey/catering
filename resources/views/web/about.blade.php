@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="bg-olive-50 py-20 border-b border-olive-100/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-xs font-bold text-olive-500 uppercase tracking-widest bg-white px-3 py-1.5 rounded-full border border-olive-100/80">Masa Depan Catering</span>
        <h1 class="font-title font-extrabold text-4xl sm:text-5xl text-gray-900 mt-4 mb-6">Tentang Dinda Catering</h1>
        <p class="text-gray-600 max-w-xl mx-auto leading-relaxed text-sm sm:text-base">
            Membawa revolusi industri catering dengan mensinergikan kelezatan rasa tradisional dan kecerdasan buatan untuk kesehatan Anda.
        </p>
    </div>
</section>

<!-- Content Grid -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center mb-24">
            <div class="relative rounded-3xl overflow-hidden aspect-video md:aspect-square bg-gray-100 shadow-xl shadow-gray-200/50">
                <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover" alt="Cooking Hands">
                <div class="absolute bottom-6 left-6 bg-white/95 backdrop-blur px-6 py-4 rounded-2xl shadow-lg border border-gray-100">
                    <p class="font-title font-bold text-3xl text-olive-500">15+</p>
                    <p class="text-xs font-semibold text-gray-500">Tahun Pengalaman Kuliner</p>
                </div>
            </div>
            <div>
                <h2 class="font-title text-3xl font-extrabold text-gray-900 mb-6">Evolusi dari Tradisi ke Inteligensi</h2>
                <p class="text-gray-500 text-sm sm:text-base leading-relaxed mb-6">
                    Dinda Catering didirikan dari hasrat mendalam akan cita rasa kuliner Indonesia. Selama bertahun-tahun, kami telah melayani ratusan klien korporat dan ribuan perayaan penting dengan dedikasi penuh.
                </p>
                <p class="text-gray-500 text-sm sm:text-base leading-relaxed mb-6">
                    Memasuki era digital, kami menyadari tantangan baru: kebutuhan nutrisi individu yang unik serta pentingnya efisiensi bagi organisasi modern. Oleh karena itu, kami meluncurkan platform Culinary AI yang merekomendasikan menu paling sesuai secara personal demi kesehatan optimal tim Anda.
                </p>
                <p class="text-gray-500 text-sm sm:text-base leading-relaxed">
                    Kini, kami bukan hanya sekadar jasa catering biasa. Kami adalah mitra teknologi nutrisi tepercaya Anda.
                </p>
            </div>
        </div>

        <!-- Vision Mission -->
        <div class="border-t border-gray-100 pt-20">
            <h2 class="font-title text-3xl font-extrabold text-gray-900 text-center mb-16">Fondasi Kepercayaan Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="bg-gray-50 p-10 rounded-3xl border border-gray-100 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-white text-olive-500 flex items-center justify-center shadow-sm mb-6">
                            👁️‍🗨️
                        </div>
                        <h3 class="font-title font-bold text-xl text-gray-900 mb-4">Visi Kami</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            Menjadi penyedia layanan catering premium terkemuka berbasis kecerdasan buatan di Asia Tenggara, menciptakan ekosistem konsumsi makan harian yang sehat, efisien, dan lezat.
                        </p>
                    </div>
                </div>
                <div class="bg-olive-500 text-white p-10 rounded-3xl flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-white/10 text-white flex items-center justify-center mb-6">
                            🎯
                        </div>
                        <h3 class="font-title font-bold text-xl mb-4 text-olive-100">Misi Kami</h3>
                        <p class="text-olive-100 text-sm leading-relaxed">
                            Mengintegrasikan teknologi AI dengan seni kuliner kelas dunia untuk menyajikan menu catering yang terbukti sehat secara medis, terjangkau secara finansial, dan disukai secara universal.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection