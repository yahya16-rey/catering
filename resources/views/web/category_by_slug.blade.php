<x-layout>
    <div class="text-center my-5">
        <h1>{{ $category->name }}</h1>
        <p class="text-muted">{{ $category->description }}</p>
    </div>

    <div class="row justify-content-center">
        @forelse ($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100 text-center p-3">
                <h3 class="fw-bold">
                    <a href="{{ route('product.detail', $product->slug) }}" class="text-decoration-none text-dark">
                        {{ $product->name }}
                    </a>
                </h3>
                <a href="{{ route('product.detail', $product->slug) }}" class="text-decoration-none text-dark">
                    @if ($product->image_url)
                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="card-img-top mb-3" style="height: 200px; object-fit: cover;">
                    @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                        <span class="fw-bold">Gambar Tidak Tersedia</span>
                    </div>
                    @endif
                </a>
                <p class="text-muted">Rp {{ number_format($product->price) }} / Box</p>
                <hr>

                {{-- Jika kamu punya list isi produk dalam bentuk teks --}}
                @php
                $features = explode(',', $product->description); // misal deskripsi dipisah koma
                @endphp

                <ul class="list-unstyled text-start">
                    @foreach ($features as $feature)
                    <li class="mb-1">
                        <i class="text-success bi bi-check-circle-fill"></i> {{ trim($feature) }}
                    </li>
                    @endforeach
                </ul>



                <a href="{{ route('product.detail', $product->slug) }}" class="btn btn-success mt-auto">
                    PESAN
                </a>


            </div>
        </div>
        @empty
        <p class="text-center">Tidak ada produk dalam kategori ini.</p>
        @endforelse
    </div>
</x-layout>