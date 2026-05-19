<!DOCTYPE html>
<x-layout>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Kategori - Widia Catering</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>

<body>

    

    {{-- Konten Kategori --}}
    <div class="container py-5">
        <div class="row justify-content-center">
            @foreach($categories as $category)
            <div class="col-md-4 text-center mb-4">
                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="category-image">
                <h5 class="mt-3 text-primary fw-semibold">
                    <a href="/category/{{ $category->slug }}">{{ $category->name }}</a>
                </h5>
                <p class="text-muted">{{ $category->description }}</p>
            </div>
            @endforeach
        </div>
    </div>



</body>



</html>
</x-layout>