<x-layouts.app :title="__('Categories')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl">Update Kategori</flux:heading>
        <flux:subheading size="lg" class="mb-6">Data Kategori</flux:heading>
        <flux:separator variant="subtle" />
    </div>

    @if(session()->has('successMessage'))
        <flux:badge color="lime" class="mb-3 w-full">{{session()->get('successMessage')}}</flux:badge>
    @elseif(session()->has('errorMessage'))
        <flux:badge color="red" class="mb-3 wf-full">{{session()->get('errorMessage')}}</flux:badge>
    @endif

    <form action="{{ route('categories.update', $category->id) }}" method="post" enctype="multipart/form-data">
        @method('patch')
        @csrf
        
        <flux:input label="Nama Kategori" name="name" value="{{ $category->name }}" class="mb-3" />

        <flux:input label="Slug" name="slug" value="{{ $category->slug }}" class="mb-3" />

        <flux:textarea label="Deskripsi" name="description" class="mb-3">{{ $category->description }}</flux:textarea>

       @if($category->image_url)
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="h-10 w-10 object-cover rounded">
                        @endif

        <flux:input type="file" label="gambar" name="image" class="mb-3" />

        <flux:separator />

        <div class="mt-4">
            <flux:button type="submit" variant="primary">Update</flux:button>
            <flux:link href="{{ route('categories.index') }}" variant="ghost" class="ml-3">Kembali</flux:link>
        </div>
    </form>
</x-layouts.app>