<x-app-layout>
    <x-slot name="title">Tambah Kategori</x-slot>

    <div class="card">
        <h2>Tambah Kategori Baru</h2>

        <form method="POST" action="{{ route('kategori.store') }}">
            @csrf

            <label>Nama Kategori</label>
            <input type="text" name="nama_kategori" class="input-box" required>

            <button class="btn-primary" style="margin-top:20px">Simpan</button>
        </form>

    </div>
</x-app-layout>
