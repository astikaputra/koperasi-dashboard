<x-app-layout>
    <x-slot name="title">Kategori Produk</x-slot>

    <div class="card">

        <div style="display:flex;justify-content:space-between;margin-bottom:20px">
            <h2>Daftar Kategori</h2>

            <a href="{{ route('kategori.create') }}">
                <button class="btn-primary">+ Tambah Kategori</button>
            </a>
        </div>

        @if(session('success'))
            <div style="background:#2ecc71;color:white;padding:10px;border-radius:6px;margin-bottom:10px">
                {{ session('success') }}
            </div>
        @endif

        <table style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($kategori as $k)
                <tr>
                    <td>{{ $k->id }}</td>
                    <td>{{ $k->nama_kategori }}</td>

                    <td>
                        <a href="{{ route('kategori.edit', $k->id) }}">
                            <button class="btn-primary" style="padding:5px 10px;font-size:0.8rem">Edit</button>
                        </a>

                        <form action="{{ route('kategori.destroy',$k->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Hapus kategori ini?')"
                                class="btn-danger"
                                style="padding:5px 10px;font-size:0.8rem;background:#e74c3c;color:white;border:0;border-radius:6px">
                                Hapus
                            </button>
                        </form>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:20px">
            {{ $kategori->links() }}
        </div>

    </div>

</x-app-layout>
