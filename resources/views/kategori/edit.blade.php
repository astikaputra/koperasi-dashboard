<x-app-layout>
    <x-slot name="title">Edit Kategori</x-slot>

    <div class="card">
        <h2>Edit Kategori</h2>

        <form method="POST" action="{{ route('kategori.update', $kategori->id) }}">
            @csrf
            @method('PUT')

            <label>Nama Kategori</label>
            <input type="text" name="nama_kategori" class="input-box" required
                   value="{{ $kategori->nama_kategori }}">

            <button class="btn-primary" style="margin-top:20px">Update</button>
        </form>

    </div>
</x-app-layout>
