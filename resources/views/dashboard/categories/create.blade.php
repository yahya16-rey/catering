<x-layouts.app :title="__('Categories')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl">Tambah Kategori</flux:heading>
        <flux:subheading size="lg" class="mb-6">Data Kategori</flux:heading>
        <flux:separator variant="subtle" />
    </div>

    @if(session()->has('successMessage'))
        <flux:badge color="lime" class="mb-3 w-full">{{session()->get('successMessage')}}</flux:badge>
    @elseif(session()->has('errorMessage'))
        <flux:badge color="red" class="mb-3 w-full">{{session()->get('errorMessage')}}</flux:badge>
    @endif

    <form action="{{ route('categories.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        
        <flux:input label="Nama Kategori" name="name" class="mb-3" />

        <flux:input label="Slug" name="slug" class="mb-3" />

        <flux:textarea label="Deskripsi" name="description" class="mb-3" />

        <flux:input type="file" label="Gambar" name="image" class="mb-3" accept="image/*"/>

        <flux:separator />

        <div class="mt-4">
            <flux:button type="submit" variant="primary">Simpan</flux:button>
            <flux:link href="{{ route('categories.index') }}" variant="ghost" class="ml-3">Kembali</flux:link>
        </div>
    </form>
</x-layouts.app>